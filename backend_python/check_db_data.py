import mysql.connector
import json

def check_db():
    try:
        conn = mysql.connector.connect(host='localhost', user='root', password='', database='ayurpredict')
        cursor = conn.cursor()
        
        # 1. Total Rows
        cursor.execute("SELECT COUNT(*) FROM recommendations")
        total = cursor.fetchone()[0]
        print(f"Total rows in 'recommendations': {total}")
        
        # 2. Check 'Vata' specifically
        cursor.execute("SELECT COUNT(*) FROM recommendations WHERE dosha='Vata'")
        vata_count = cursor.fetchone()[0]
        print(f"Rows for 'Vata': {vata_count}")
        
        # 3. Check Categories
        cursor.execute("SELECT DISTINCT category FROM recommendations")
        cats = cursor.fetchall()
        print(f"Categories found: {[c[0] for c in cats]}")
        
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    check_db()
