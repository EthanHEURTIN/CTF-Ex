#include <stdio.h>
#include <unistd.h>
#include <string.h>

int get_c() {
    char name[67];

    printf("What's your name : ");
    fflush(stdout);

    int r = read(0, name, 400);

    if (strcasecmp(name, "velkoz") == 0 || strcasecmp(name, "velkoz\n") == 0) {
        printf("\n>>> Hi my brother Vel'koz ! <<<\n");
        printf("I escaped from Marex, he wasn't feeding me anymore, that bloody bastard. I'm safe, believe me.\n\n");
    } else {
        printf("I DON'T WANT TO TALK TO YOU !\n");
        printf("GET C !\n\n");
    }

    return 0;
}

int main(int argc, char *argv[]) {

    get_c();

    return 0;
}