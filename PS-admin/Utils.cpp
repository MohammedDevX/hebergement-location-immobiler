#include "Utils.h"
#include <conio.h>
#include <string>
#include <limits>
#include <iostream>
using namespace std;
string SaisirMotDePasse() { //bach maybanch lmdp fach ykon ykatbo, ybayan ghi *****
    string mdp;
    char ch;


    while ((ch = _getch()) != '\r') { // _getch katdir b7al cin walakin makadirloch l affichage   '\r' hiya enter , lboucle trayad fach ywta 3la entrer
        if (ch == '\b') { // \b hiya lbotona li katm7i biha
            if (!mdp.empty()) {
                cout << "\b \b"; // radi ym7i * li katkon mn affichage
                mdp.pop_back();     //ym7ih 7ta mn mdp
            }
        } else {
            mdp.push_back(ch);
            cout << '*';
        }
    }

    cout << endl; // Move to the next line after Enter
    return mdp;
}

void pause() {
        cout << "\nAppuyez sur Entree pour continuer...";
        cin.get();
        cout << "\n" << endl;
    }

string EscapeString(MYSQL* conn, const string& input) {
    char* buffer = new char[input.length() * 2 + 1];
    mysql_real_escape_string(conn, buffer, input.c_str(), input.length());
    string escaped(buffer);
    delete[] buffer;
    return escaped;
}

void ClearInputBuffer() {
    cin.clear();
    cin.ignore(numeric_limits<streamsize>::max(), '\n');
}


