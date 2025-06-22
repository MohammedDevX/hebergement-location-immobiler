#ifndef ANNONCE_H
#define ANNONCE_H
#include "MySQLConnection.h"
#include<string>
#include<vector>
#include<iostream>
using namespace std;

class Avis;
class Hote;
class Reservation;

class Annonce
{
    public:
        Annonce(MYSQL* conn,const int ID_annonce=0,const string titre="", const string description="",
                 const string Adresse="",const float prix=0,const string date="",const string propriete_type="",const string ville="",const int capacite=0,const string nom_hote="");

        ~Annonce();

        void Afficher_Annonce_Hote();
        void Set_Hote(const Hote* );
        void Remplir_Disponibilities(MYSQL* conn);
        void Afficher_Disponibilities();
        void Remplir_Reservations(MYSQL* conn);
        void Afficher_Reservations() const;
        void Supprimer_Annonce(MYSQL* conn);
        void Remplir_Avis(MYSQL*);
        void Afficher_Avis() const;
        void Afficher_An() ;
        void Afficher_Annonce();


    private:
        int ID_Annonce;
        string Titre;
        string Description;
        string Adresse;
        int Capacite;
        float Prix_Nuit;
        vector<string> Disponibilities;
        string Date_Creation;
        string Propriete_Type;
        string Ville;
        vector<Avis*> Annonce_Avis;
        const Hote* Annonce_Hote;
        string Nom_Hote;
        vector<Reservation*> Annonce_Reservations;

};

#endif // ANNONCE_H
