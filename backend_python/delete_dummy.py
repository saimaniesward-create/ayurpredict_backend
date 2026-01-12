import mysql.connector

def cleanup():
    try:
        conn = mysql.connector.connect(host='localhost', user='root', password='', database='ayurpredict')
        cursor = conn.cursor()
        
        # 1. Delete Dummy User 1
        cursor.execute("DELETE FROM dosha_scores WHERE user_id = 1")
        cursor.execute("DELETE FROM daily_checkins WHERE user_id = 1")
        conn.commit()
        print("✅ Dummy Data (User 1) DELETED.")

        # 2. Check User 15 (The Real User)
        cursor.execute("SELECT COUNT(*) FROM dosha_scores WHERE user_id = 15")
        count = cursor.fetchone()[0]
        
        if count == 0:
            print("⚠️ WARNING: User 15 has NO Check-In Data. Recommendations will be BLANK.")
        else:
            print(f"✅ User 15 has {count} records. Recommendations should work.")
            
            # Show the latest record for User 15
            cursor.execute("SELECT * FROM dosha_scores WHERE user_id = 15 ORDER BY checkin_date DESC LIMIT 1")
            row = cursor.fetchone()
            print(f"   Latest Score: {row}")

    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    cleanup()
