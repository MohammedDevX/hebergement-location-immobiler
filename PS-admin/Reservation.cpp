#include "Reservation.h"

Reservation::Reservation(const int ID_reservation, const string date_debut, const string date_fin,const string statut, const string nom_locataire)
{
    ID_Reservation=ID_reservation;
    Date_Debut=date_debut;
    Date_Fin=date_fin;
    Statut=statut;
    Nom_Locataire=nom_locataire;

}

Reservation::~Reservation()
{

}

void Reservation::Afficher_Reservation() const
{
    cout<<"de: "<<Date_Debut<<"  a: "<<Date_Fin<<", par :"<<Nom_Locataire<<endl;
}

void Reservation::Set_Annonce(Annonce* A)
{
    Annonce_Reservation=A;
}
