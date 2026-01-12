
import json
import sys
import os

# Add current directory to path
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from chat_bot import load_intents, train_model, get_response

# List of 50 Test Questions covering Herbs, Check-ins, and General Ayurveda
test_questions = [
    # --- Greetings & General ---
    "Hello",
    "What is Ayurveda?",
    "Thank you",
    "Bye",
    
    # --- Doshas ---
    "What is Vata?",
    "Pitta diet tips",
    "Kapha symptoms",
    
    # --- Daily Check-ins (All 17 inputs) ---
    "Why do I crave spicy food?",
    "Sweet craving meaning",
    "Body heaviness meaning",
    "What is bowel movement?",
    "Sleep quality meaning",
    "Why am I so stressed?",
    "Morning energy meaning",
    "Why does my body feel hot?",
    "Why is my skin dry?",
    "Hydration level meaning",
    "Why am I irritable?",
    "Physical activity meaning",
    "Variable appetite meaning",
    "Digestion meaning",
    
    # --- Herbs (Selection) ---
    "What is Ashwagandha?",
    "Benefits of Tulsi",
    "Neem uses",
    "Tell me about Triphala",
    "What is Turmeric?",
    "Ginger benefits",
    "Garlic uses",
    "What is Aloe Vera?",
    "Amalaki benefits",
    "Bhringaraj for hair",
    "Bibhitaki uses",
    "Black pepper benefits",
    "Brahmi for memory",
    "Cardamom uses",
    "Sandalwood benefits",
    "Chitrak for weight loss",
    "Coriander uses",
    "Cumin benefits",
    "Fennel seeds uses",
    "Fenugreek benefits",
    "Guduchi immunity",
    "Guggul for cholesterol",
    "Haritaki benefits",
    "Jatamansi for sleep",
    "Licorice uses",
    "Manjistha benefits",
    "Mint uses",
    "Nutmeg for sleep",
    "Shatavari for women"
]

print("-" * 60)
print(f"Running Batch Test on {len(test_questions)} Questions...")
print("-" * 60)

# Load and Train ONCE (Simulating Server Load)
current_dir = os.path.dirname(os.path.abspath(__file__))
intents_path = os.path.join(current_dir, 'intents.json')

try:
    data = load_intents(intents_path)
    vec, X_matrix, tag_list, _ = train_model(data)
    print("Model Trained Successfully.")
    print("-" * 60)

    for i, q in enumerate(test_questions, 1):
        reply = get_response(q, vec, X_matrix, tag_list, data)
        print(f"Q{i}: {q}")
        print(f"A:  {reply}")
        print("-" * 60)

except Exception as e:
    print(f"FATAL ERROR: {str(e)}")
