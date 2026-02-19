import random
import os
import time

def roulette():
    chiffre = random.randint(1, 3)
    print(f"Lottery result : {chiffre}")

    if chiffre == 2:
        print("RESPECT, 2% OF LUCK :BASED:")
        time.sleep(3)
    else:
        print("HAH too guez !")

if __name__ == "__main__":
    roulette()
