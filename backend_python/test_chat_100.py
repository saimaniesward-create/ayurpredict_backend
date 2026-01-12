
import json
import sys
import os
import random

# Add current directory to path
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from chat_bot import load_intents, train_model, get_response

# 100 Test Questions Covering All Categories
test_questions = [
    # --- GREETINGS & GENERAL (5) ---
    "Hello",
    "Good morning",
    "What is Ayurveda?",
    "Who are you?",
    "Bye",

    # --- DOSHA BASICS (5) ---
    "What is Vata dosha?",
    "Pittsa characteristics",
    "Kapha body type",
    "How to balance Vata?",
    "Diet for Pitta",

    # --- DAILY CHECK-INS (17) ---
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
    "I feel lethargic",
    "Meaning of elimination in Ayurveda",
    "Why am I always cold?",

    # --- HERBS (37) ---
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
    "Shatavari for women",
    "Benefits of Amla",
    "Is Giloy good for fever?",
    "Uses of Yashtimadhu",
    "Arjuna for heart",
    "Shilajit benefits",
    "Moringa uses",
    "Gotu Kola for brain",
    "Kalmegh for lever",

    # --- YOGA & BREATHING (33) ---
    # Vata
    "Yoga for Vata",
    "How to do Mountain Pose?",
    "How to do Tree Pose?",
    "How to do Warrior I?",
    "How to do Child's Pose?",
    "How to do Corpse Pose?",
    "How to do Legs Up the Wall?",
    "How to do Seated Forward Bend?",
    "How to do Bridge Pose?",
    "How to do Thunderbolt Pose?",
    "How to do Nadi Shodhana?",
    
    # Pitta
    "Yoga for Pitta",
    "How to do Moon Salutation?",
    "How to do Cobra Pose?",
    "How to do Fish Pose?",
    "How to do Bow Pose?",
    "How to do Shoulder Stand?",
    "How to do Standing Forward Bend?",
    "How to do Pigeon Pose?",
    "How to do Camel Pose?",
    "How to do Supine Twist?",
    "How to do Sheetali Pranayama?",

    # Kapha
    "Yoga for Kapha",
    "How to do Sun Salutation?",
    "How to do Warrior II?",
    "How to do Triangle Pose?",
    "How to do Boat Pose?",
    "How to do Chair Pose?",
    "How to do Plank Pose?",
    "How to do Locust Pose?",
    "How to do Kapalabhati?",
    "How to do Lion's Breath?",
    
    # Random Mix for the last few to hit 100 matches
    "Best breath for anxiety",
    "Yoga for weight loss",
    "Cooling breath",
    "Grounding yoga",
    "Energizing yoga"
]

# Shuffle mainly to simulate random user input flow, but list is fixed for coverage
# random.shuffle(test_questions) 

print("-" * 60)
print(f"Running Batch Test on {len(test_questions)} Questions...")
print("-" * 60)

# Load and Train ONCE
current_dir = os.path.dirname(os.path.abspath(__file__))
intents_path = os.path.join(current_dir, 'intents.json')

try:
    data = load_intents(intents_path)
    # Re-using the train_model function from chat_bot.py
    # Note: Ensure chat_bot.py has these functions accessible or copied here if they are inside 'if __name__'
    vec, X_matrix, tag_list, _ = train_model(data)
    print("Model Trained Successfully.")
    print("-" * 60)

    pass_count = 0
    
    for i, q in enumerate(test_questions, 1):
        reply = get_response(q, vec, X_matrix, tag_list, data)
        print(f"Q{i}: {q}")
        print(f"A:  {reply}")
        
        # Simple heuristic for pass/fail (not perfect, but checks if fallback was triggered)
        if "I am still learning" not in reply and "I am confused" not in reply:
             pass_count += 1
        
        print("-" * 60)
        
    print(f"TEST COMPLETE. Passed (Approx): {pass_count}/{len(test_questions)}")

except Exception as e:
    print(f"FATAL ERROR: {str(e)}")
