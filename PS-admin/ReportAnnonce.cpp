#include "ReportAnnonce.h"
#include <sstream>
#include"Utils.h"
#include "Annonce.h"
ReportAnnonce::ReportAnnonce(MYSQL* conn,const int&ID_rep,const string&message,const bool&statut,const string&date,const string&nom)
{
    ID_ReportAnnonce=ID_rep;
    Message=message;
    Statut=statut;
    Nom_Locataire=nom;
    Date=date;

    RemplirAnnonce(conn);
}

ReportAnnonce::~ReportAnnonce()
{

}

void ReportAnnonce::RemplirAnnonce(MYSQL* conn)
{
    ostringstream query;
    query << "SELECT  an.id_annonce,an.titre,an.description_annonce,an.created_at,an.prix_nuit,an.adresse,an.capacite,an.type_logement,v.nom_ville,l.prenom,l.nom "
    << "FROM hote h "
          << "JOIN locataire l ON h.id_locataire = l.id_locataire "
          << "JOIN annonce an ON an.id_hote = h.id_hote "
          << "JOIN report_annonce ra ON ra.id_annonce = an.id_annonce "
          << "JOIN ville v ON an.id_ville = v.id_ville "
          << "WHERE ra.id_report_annonce= "
          << ID_ReportAnnonce;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        MYSQL_RES* result = mysql_store_result(conn);
        MYSQL_ROW row;

        while ((row = mysql_fetch_row(result))) {
            int id_annonce = atoi(row[0]);
            string titre = row[1] ? row[1] : "";
            string description = row[2] ? row[2] : "";
            string date=row[3] ? row[3] : "";
            float prix = stof(row[4]);
            string adresse=row[5] ? row[5] : "";
            int capacite= atoi(row[6]);
            string type = row[7]?row[7]:"";
            string ville=row[8] ? row[8] : "";
            string nomhote=row[9] ? row[9] : "";
            nomhote+=" ";
            nomhote+= row[10] ? row[10] : "";

            annonce = new Annonce(conn,id_annonce,titre,description,adresse, prix,date,type,ville,capacite,nomhote);
        }

        mysql_free_result(result);
    } else {
        cerr << "Erreur lors du chargement des annonces reportees : " << mysql_error(conn) << endl;
    }
}

void ReportAnnonce::Afficher_Report()
{
    cout<<"--------------  Report  --------------"<<endl;
    cout<<"ID Report: "<<ID_ReportAnnonce<<endl;
    cout<<"Reporter par: "<<Nom_Locataire<<endl;
    cout<<"Le: "<<Date<<endl;
    cout<<"Raison: "<<Message<<endl;
    cout<<"Statut: ";
    Afficher_Statut();

    annonce->Afficher_Annonce();
    cout<<"-----------------   -----------------"<<endl;
    ClearInputBuffer();
    pause();
}

void ReportAnnonce::Afficher_Statut()
{
    if(Statut==0) cout<<"En cours d'examen"<<endl;
    else cout<<"Résolu"<<endl;
}

