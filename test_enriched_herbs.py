from backend_python.chat_bot import get_chat_response

print("Testing Enriched Herb Responses:\n")

# Test 1: Punarnava
response1 = get_chat_response("What is Punarnava?")
print(f"Q: What is Punarnava?\nA: {response1}\n")

# Test 2: Aloe Vera
response2 = get_chat_response("Benefits of Aloe Vera")
print(f"Q: Benefits of Aloe Vera\nA: {response2}\n")

# Test 3: Ashwagandha
response3 = get_chat_response("Tell me about Ashwagandha")
print(f"Q: Tell me about Ashwagandha\nA: {response3}\n")
