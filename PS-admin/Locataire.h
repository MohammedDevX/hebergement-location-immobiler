#ifndef LOCATAIRE_H
#define LOCATAIRE_H
#include "MySQLConnection.h"
#include<string>
#include<vector>
#include<iostream>
#include "Utils.h"
#include "Admin.h"
using namespace std;

class Avis;

class Locataire
{
    public:
        Locataire(const int &ID=0, const string&nom="nom", const string&prenom="prenom", const string& email="Email@gmail.com", const int&num=0600000000);
        Locataire(MYSQL* conn,const int &ID=0, const string&nom="nom", const string&prenom="prenom", const string& email="Email@gmail.com", const int&num=0600000000,const string date_naissance=" ",const string date_creation=" ");
        Locataire(const Locataire&);
        ~Locataire();
        void Afficher_Locataire() const;
        void Afficher_info() const;
        int Get_ID_Locataire() const;
        void Ajouter_Avis(const int, const int&, const string&, const string&);
        void Afficher_Les_Avis() const;
        void Remplir_Avis(MYSQL* conn);
        int Get_ID();


    protected:
        int ID_Locataire;
        string Nom;
        string Prenom;
        string Email;
        int Num_Tele;
        string Date_Naissance;
        string Date_Creation;
        vector<Avis*> Les_Avis;
};

#endif // LOCATAIRE_H
