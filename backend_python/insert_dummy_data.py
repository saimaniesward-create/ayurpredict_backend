import mysql.connector
def insert_dummy():
    try:
        conn = mysql.connector.connect(host='localhost', user='root', password='', database='ayurpredict')
        cursor = conn.cursor()
        
        # Insert Scores for User 1
        # Vata=80, Pitta=50, Kapha=20 (Clearly Vata Dominant)
        cursor.execute("""
            INSERT INTO dosha_scores (user_id, vata_score, pitta_score, kapha_score, checkin_date) 
            VALUES (1, 80, 50, 20, NOW())
            ON DUPLICATE KEY UPDATE vata_score=80, pitta_score=50, kapha_score=20
        """)
        
        # Insert Checkin for User 1 (needed for personalization logic if any)
        cursor.execute("INSERT INTO daily_checkins (user_id) VALUES (1)")
        
        conn.commit()
        print("Dummy data inserted for User 1.")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    insert_dummy()
