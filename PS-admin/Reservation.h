#ifndef RESERVATION_H
#define RESERVATION_H
#include<string>
#include<vector>
#include<iostream>
using namespace std;

class Annonce;
class Reservation
{
    public:
        Reservation(const int ID_reservation=0, const string date_debut="2025-01-01", const string date_fin="2025-01-02" ,const string Statut="an attente", const string nom_locataire="nom");
        ~Reservation();
        void Afficher_Reservation() const;
        void Set_Annonce(Annonce* A);


    private:
        int ID_Reservation;
        string Date_Debut;
        string Date_Fin;
        string Statut;
        string  Nom_Locataire;
        Annonce* Annonce_Reservation;
};

#endif // RESERVATION_H
