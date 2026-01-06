import mysql.connector
from mysql.connector import Error

def create_db_connection():
    """
    Establishes a connection to the XAMPP MySQL database.
    """
    connection = None
    try:
        connection = mysql.connector.connect(
            host='localhost',
            user='root',           # Default XAMPP user
            password='',           # Default XAMPP password is empty
            database='ayurpredict' # Your database name
        )
        if connection.is_connected():
            print("SUCCESS: Connected to XAMPP MySQL Database 'ayurpredict'")
            return connection
            
    except Error as e:
        print(f"ERROR: '{e}'")
        return None

def close_connection(connection):
    if connection and connection.is_connected():
        connection.close()
        print("Connection closed.")

if __name__ == '__main__':
    # Test the connection
    conn = create_db_connection()
    if conn:
        # Example: Fetch 5 herbs to prove it works
        cursor = conn.cursor()
        cursor.execute("SELECT name, dosha FROM herbs LIMIT 5")
        rows = cursor.fetchall()
        print("\n--- TEST QUERY: First 5 Herbs ---")
        for row in rows:
            print(f"Herb: {row[0]} | Dosha: {row[1]}")
        
        close_connection(conn)
