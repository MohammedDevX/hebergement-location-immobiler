#include "Annonce.h"
#include "Reservation.h"
#include <sstream>
#include"Avis.h"
#include"Admin.h"
#include"Utils.h"
Annonce::Annonce(MYSQL* conn,const int ID_annonce,const string titre,const string description,const string adresse,const float prix,const string date,const string propriete_type,const string ville,const int capacite,const string nom_hote)
{
    ID_Annonce=ID_annonce;
    Titre=titre;
    Description=description;
    Prix_Nuit=prix;
    Date_Creation=date;
    Propriete_Type=propriete_type;
    Ville=ville;
    Adresse=adresse;
    Capacite=capacite;
    Nom_Hote=nom_hote;

    Remplir_Disponibilities(conn);
    Remplir_Reservations(conn);
    Remplir_Avis(conn);
}

Annonce::~Annonce()
{
    for (size_t i = 0; i < Annonce_Reservations.size(); i++) {
        delete Annonce_Reservations[i];
    }
    Annonce_Reservations.clear();
    Annonce_Reservations.shrink_to_fit();

    for (size_t i = 0; i < Annonce_Avis.size(); i++) {
        delete Annonce_Avis[i];
    }
    Annonce_Avis.clear();
    Annonce_Avis.shrink_to_fit();

    Disponibilities.clear();
    Disponibilities.shrink_to_fit();



}

void Annonce::Afficher_An()
{

    char r,detail;
    cout<<"Titre: "<<Titre<<endl;
    cout<<"Description: "<<Description<<endl;
    cout<<"Prix par Nuit: "<<Prix_Nuit<<endl;
    cout<<"Type de Propriete: "<<Propriete_Type<<endl;
    cout<<"Ville: "<<Ville<<endl;
    cout<<"Dispnoible pour "<<Disponibilities.size()<<" jour(s)"<<endl;
    cout<<"Reservations:  "<<Annonce_Reservations.size()<<endl;
    cout<<"A:  "<<Annonce_Avis.size()<<" Avis"<<endl;
    do{
        cout<<"Voullez vous consulter les details de l'Annonce(Disponibilities/Reservations/Avis) ? (y/n)"<<endl;
        cin>>detail;
    }while(detail!='y' && detail!='n');
    if(detail=='n'){
        cout<<"\\----------------  ----------------/"<<endl<<endl;
        return;
    }

    do{
        cout<<"Voullez vous voir les dates inDisponible? (y/n)"<<endl;
        cin>>r;
    }while(r!='y' && r!='n');

    if(r=='y')Afficher_Disponibilities();


    do{
        cout<<"Voullez vous voir les Reservations? (y/n)"<<endl;
        cin>>r;
    }while(r!='y' && r!='n');

    if(r=='y')Afficher_Reservations();


    do{
        cout<<"Voullez vous voir les Avis? (y/n)"<<endl;
        cin>>r;
    }while(r!='y' && r!='n');

    if(r=='y')Afficher_Avis();


    cout<<"\\----------------  ----------------/"<<endl<<endl;

    pause();
    ClearInputBuffer();
}

void Annonce::Afficher_Annonce()
{
    cout<<"/------------  Annonce  ------------\\"<<endl;
    cout<<"ID Annonce: "<<ID_Annonce<<endl;
    cout<<"Nom d' Hote: "<<Nom_Hote<<endl;

    Afficher_An();
}

void Annonce::Afficher_Annonce_Hote()
{
    cout<<"/------------  Annonce  ------------\\"<<endl;
    cout<<"ID Annonce:"<<ID_Annonce<<endl;
    Afficher_An();
}

void Annonce::Set_Hote(const Hote* A)
{
    Annonce_Hote=A;
}

void Annonce::Remplir_Disponibilities(MYSQL* conn)
{
    ostringstream query;
    query << "SELECT  d.date_dispo "
    <<"FROM annonce an join disponibilite d on an.id_annonce=d.id_annonce WHERE an.id_annonce  =" << ID_Annonce;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        MYSQL_RES* result = mysql_store_result(conn);
        MYSQL_ROW row;

        while ((row = mysql_fetch_row(result))) {
            string dispo = row[0] ? row[0] : "";

            Disponibilities.push_back(dispo);
        }

        mysql_free_result(result);
    } else {
        cerr << "Erreur lors du chargement des Disponibilities : " << mysql_error(conn) << endl;
    }
}

void Annonce::Afficher_Disponibilities()
{
        for(size_t i=0; i<Disponibilities.size();i++)
    {
        cout<<"\t '"<<Disponibilities[i]<<"'"<<endl;
    }
    cout<<endl;
}

void Annonce::Remplir_Reservations(MYSQL* conn)
{
    ostringstream query;
    query << "SELECT r.id_reservation,r.date_debut, r.date_fin,l.nom,l.prenom  "
    <<"FROM locataire l join reservation r on l.id_locataire=r.id_locataire join annonce an on r.id_annonce=an.id_annonce WHERE an.id_annonce  =" << ID_Annonce;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        MYSQL_RES* result = mysql_store_result(conn);
        MYSQL_ROW row;

        while ((row = mysql_fetch_row(result))) {
            int id_reservation = atoi(row[0]);
            string date_debut = row[1] ? row[1] : "";
            string date_fin = row[2] ? row[2] : "";
            string nom=row[3] ? row[3] : "";
            nom+=" ";
            nom+=row[4] ? row[4] : "";


            Reservation* reservation = new Reservation(id_reservation,date_debut,date_fin,"",nom);
            reservation->Set_Annonce(this);
            Annonce_Reservations.push_back(reservation);
        }

        mysql_free_result(result);
    } else {
        cerr << "Erreur lors du chargement des Reservations : " << mysql_error(conn) << endl;
    }
}

void Annonce::Afficher_Reservations() const
{
        for(size_t i=0; i<Annonce_Reservations.size();i++)
    {
        Annonce_Reservations[i]->Afficher_Reservation();
    }
        cout<<endl;
}

void Annonce::Supprimer_Annonce(MYSQL* conn)
{
    ostringstream query;
    query << "DELETE FROM annonce "
          << "WHERE id_annonce = " << ID_Annonce ;

    if (mysql_query(conn, query.str().c_str()) != 0) {
        cerr << "Erreur lors de la suppression: " << mysql_error(conn) << endl;
    } else {
        cout << "Annonce supprimee avec succes!" << endl;
    }
}

void Annonce::Remplir_Avis(MYSQL* conn)
{
    ostringstream query;
    query << "SELECT av.id_avis,l.nom,l.prenom,av.note,av.commentaire,av.created_at "
    <<"FROM locataire l join avis av on l.id_locataire=av.id_locataire join annonce an on an.id_annonce=av.id_annonce WHERE an.id_annonce = " << ID_Annonce;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        MYSQL_RES* result = mysql_store_result(conn);
        MYSQL_ROW row;

        while ((row = mysql_fetch_row(result))) {
            int id_avis = atoi(row[0]);
            string nom = row[2] ? row[2] : "";
            nom+= " ";
            nom+=  row[1] ? row[1] : "";
            int note = atoi(row[3]);
            string commentaire=row[4] ? row[4] : "";
            string date=row[5] ? row[5] : "";


            Avis* avis = new Avis(id_avis,nom,note,commentaire,date);
            avis->Set_Annonce(this);
            Annonce_Avis.push_back(avis);
        }

        mysql_free_result(result);
    } else {
        cerr << "Erreur lors du chargement des Reservations : " << mysql_error(conn) << endl;
    }
}

void Annonce::Afficher_Avis() const
{
        for(size_t i=0; i<Annonce_Avis.size();i++)
    {
        Annonce_Avis[i]->Afficher_Avis_Author();
    }
        cout<<endl;
}
