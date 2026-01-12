
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))
from chat_bot import load_intents, train_model, get_response

# Path to intents
intents_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'intents.json')

# Load and Train
data = load_intents(intents_path)
vec, X_matrix, tag_list, _ = train_model(data)

# Test Question
q = "Best breath for anxiety"
response = get_response(q, vec, X_matrix, tag_list, data)

print(f"Q: {q}")
print(f"A: {response}")

if "HOW TO: Close right nostril" in response:
    print("PASS: Specific Nadi Shodhana intent triggered.")
else:
    print("FAIL: Still triggering general intent.")
