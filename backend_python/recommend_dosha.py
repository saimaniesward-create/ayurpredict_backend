import sys
import json
import datetime
import os

# --- 1. GLOBAL LOGGING SETUP ---
# We setup logging immediately to catch import errors or early failures
LOG_FILE = os.path.join(os.path.dirname(__file__), "debug_python.log")

def log_debug(msg):
    try: 
        with open(LOG_FILE, "a") as f:
            f.write(f"[{datetime.datetime.now()}] {msg}\n")
    except:
        pass 

# Print log location for debugging visibility (Removed for Production Compliance)
# print(f"DEBUG_LOG_PATH: {os.path.abspath(LOG_FILE)}")

log_debug("--- Script Started ---")

# --- 2. ROBUST IMPORTS ---
try:
    import mysql.connector
    from mysql.connector import Error
    log_debug("MySQL Connector imported successfully.")
except ImportError as e:
    log_debug(f"CRITICAL: Failed to import mysql.connector: {e}")
    print(json.dumps({"status": "error", "message": "Missing mysql-connector-python"}))
    sys.exit(1)

# Import ML libraries (Optional - Fallback if missing)
ML_AVAILABLE = False
# [OPTIMIZATION] Disabled heavy libraries for speed (Reduces load time from 7s to 0.5s)
# try:
#     import joblib
#     import pandas as pd
#     ML_AVAILABLE = True
#     log_debug("ML libraries (joblib, pandas) imported successfully.")
# except ImportError as e:
#     log_debug(f"WARNING: ML libraries missing ({e}). Switching to Rule-Based Mode.")

# --- 3. DATABASE CONNECTION ---
def create_db_connection():
    connection = None
    try:
        connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='ayurpredict'
        )
        if connection.is_connected():
            log_debug("Database connected successfully.")
    except Error as e:
        log_debug(f"Database connection FAILED: {e}")
    return connection

# --- 4. MAIN LOGIC ---
def get_recommendations(user_id):
    log_debug(f"Processing for User ID: {user_id}")
    
    conn = create_db_connection()
    if not conn:
        print(json.dumps({"status": "error", "message": "Database connection failed"}))
        return

    try:
        cursor = conn.cursor(dictionary=True)

        # A. Fetch Scores
        query_scores = """
            SELECT vata_score, pitta_score, kapha_score 
            FROM dosha_scores 
            WHERE user_id = %s 
            ORDER BY checkin_date DESC 
            LIMIT 1
        """
        cursor.execute(query_scores, (user_id,))
        row = cursor.fetchone()

        if not row:
            log_debug("No scores found for user.")
            print(json.dumps({"status": "error", "message": "No check-in data found"}))
            return
        
        log_debug(f"Scores Found: Vata={row['vata_score']}, Pitta={row['pitta_score']}, Kapha={row['kapha_score']}")

        scores = {
            "Vata": row['vata_score'], 
            "Pitta": row['pitta_score'], 
            "Kapha": row['kapha_score']
        }
        
        # B. Define Dominant Dosha (AI or Rule-Based)
        dominant_dosha = None
        
        if ML_AVAILABLE:
            model_path = os.path.join(os.path.dirname(__file__), 'dosha_model.pkl')
            if os.path.exists(model_path):
                try:
                    model = joblib.load(model_path)
                    input_df = pd.DataFrame([[scores['Vata'], scores['Pitta'], scores['Kapha']]], columns=['Vata', 'Pitta', 'Kapha'])
                    prediction = model.predict(input_df)[0]
                    log_debug(f"AI Prediction: {prediction}")
                    
                    if "-" in prediction:
                        dominant_dosha = prediction.split("-")[0]
                    else:
                        dominant_dosha = prediction
                except Exception as ml_e:
                    log_debug(f"AI Prediction Error: {ml_e}")
            else:
                log_debug("Model file (dosha_model.pkl) not found.")

        if not dominant_dosha:
            # Fallback
            sorted_scores = sorted(scores.items(), key=lambda item: item[1], reverse=True)
            dominant_dosha = sorted_scores[0][0]
            log_debug(f"Fallback Dominant: {dominant_dosha}")

        # Determine Lowest for support
        sorted_scores_all = sorted(scores.items(), key=lambda item: item[1], reverse=True)
        lowest_dosha = sorted_scores_all[2][0]

        # C. Recommendation Logic (Dynamic)
        targets = []
        primary_dom = dominant_dosha
        dom_score = scores.get(primary_dom, 0)
        depleted_score = scores.get(lowest_dosha, 0)

        log_debug(f"Logic Check: DomScore={dom_score}, DepletedScore={depleted_score}")

        if dom_score > 60:
            log_debug("Condition: High Imbalance (>60)")
            targets.append({"dosha": primary_dom, "limit": 8})
            if depleted_score <= 30:
                log_debug("Condition: Adding Depleted Support (<=30)")
                targets.append({"dosha": lowest_dosha, "limit": 4})
        else:
            log_debug("Condition: Balanced (<=60)")
            targets.append({"dosha": primary_dom, "limit": 10})

        # D. Fetch Data
        final_food = []
        final_yoga = []
        final_life = []
        final_herbs = []
        categories = ['food', 'yoga', 'lifestyle']
        # base_image_url = "http://10.0.2.2/ayurpredict/images/herbs/" # Android Emulator
        base_image_url = "http://10.65.241.223/ayurpredict/images/herbs/" # User's Real IP

        for target in targets:
            d_name = target['dosha']
            limit_val = target['limit']
            
            # Tips
            for cat in categories:
                q_recs = "SELECT content FROM recommendations WHERE dosha = %s AND category = %s LIMIT %s"
                cursor.execute(q_recs, (d_name, cat, limit_val))
                rows = cursor.fetchall()
                log_debug(f"Fetched {len(rows)} {cat} items for {d_name}")
                
                for r in rows:
                    if cat == 'food': final_food.append(r['content'])
                    elif cat == 'yoga': final_yoga.append(r['content'])
                    elif cat == 'lifestyle': final_life.append(r['content'])

            # Herbs (Detailed)
            q_herbs = "SELECT name, description, image_filename, benefits, usage_dosage, usage_preparation, usage_time, precautions FROM herbs WHERE dosha = %s LIMIT %s"
            cursor.execute(q_herbs, (d_name, limit_val))
            h_rows = cursor.fetchall()
            log_debug(f"Fetched {len(h_rows)} herbs for {d_name}")

            for h in h_rows:
                img = base_image_url + h['image_filename'] if h['image_filename'] else None
                final_herbs.append({
                    "name": h['name'], 
                    "description": h['description'] if h['description'] else "Ayurvedic Herb",
                    "benefits": h['benefits'] if h['benefits'] else "Promotes general immunity.",
                    "usage_dosage": h['usage_dosage'] if h['usage_dosage'] else "As directed.",
                    "usage_preparation": h['usage_preparation'] if h['usage_preparation'] else "Powder.",
                    "usage_time": h['usage_time'] if h['usage_time'] else "Morning.",
                    "precautions": h['precautions'] if h['precautions'] else "None.",
                    "image": img
                })

        # E. Final Response (MATCHING LEGACY PHP STRUCTURE EXACTLY)
        # The Android App expects: recommendations -> {DominantDoshaName} -> {food, yoga...}
        
        # We constructed flat lists, now we nest them.
        nested_data = {
            "food": final_food,
            "yoga": final_yoga,
            "lifestyle": final_life,
            "herbs": final_herbs
        }

        response = {
            "status": "success",
            "dominant_dosha": dominant_dosha,
            "lowest_dosha": lowest_dosha,
            "recommendations": {
                dominant_dosha: nested_data # <--- CRITICAL NESTING
            },
            "ai_mode": ML_AVAILABLE
        }
        
        log_debug("Success. Returning JSON (Legacy Format).")
        print(json.dumps(response))

    except Exception as e:
        log_debug(f"CRITICAL ERROR: {e}")
        print(json.dumps({"status": "error", "message": str(e)}))
    finally:
        if conn and conn.is_connected():
            conn.close()

if __name__ == "__main__":
    if len(sys.argv) < 2:
        log_debug("Error: No User ID provided.")
        print(json.dumps({"status": "error", "message": "User ID required"}))
    else:
        get_recommendations(sys.argv[1])
