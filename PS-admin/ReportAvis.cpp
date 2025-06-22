#include "ReportAvis.h"
#include <sstream>
#include"Utils.h"
#include "Avis.h"

ReportAvis::ReportAvis(MYSQL* conn,const int&ID_rep,const string&message,const bool&statut,const string&date,const string&nom)
{
    ID_ReportAvis=ID_rep;
    Message=message;
    Statut=statut;
    Nom_Locataire=nom;
    Date=date;

    RemplirAvis(conn);
}

ReportAvis::~ReportAvis()
{
    //dtor
}

void ReportAvis::RemplirAvis(MYSQL* conn)
{
    ostringstream query;
    query << "SELECT av.id_avis, av.note, av.commentaire, av.created_at,an.titre,l.prenom,l.nom "
        << "FROM locataire l "
        << "JOIN avis av ON av.id_locataire = l.id_locataire "
        << "JOIN annonce an ON an.id_annonce=av.id_annonce "
        << "JOIN report_avis ra ON ra.id_avis = av.id_avis "
        << "WHERE ra.id_report_avis= "
        << ID_ReportAvis;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        MYSQL_RES* result = mysql_store_result(conn);
        MYSQL_ROW row;

        while ((row = mysql_fetch_row(result))) {
            int id_avis = atoi(row[0]);
            int note = atoi(row[1]);
            string commentaire = row[2] ? row[2] : "";
            string date = row[3] ? row[3] : "";
            string titre=row[4] ? row[4] : "";
            string nom=row[5] ? row[5] : "";
            nom+=" ";
            nom+=row[6] ? row[6] : "";

            avis = new Avis(id_avis, note, commentaire, date,titre,nom);
        }

        mysql_free_result(result);
    } else {
        cerr << "Erreur lors du chargement des avis reportees : " << mysql_error(conn) << endl;
    }

}

void ReportAvis::Afficher_Report()
{
    cout<<"/-------------  Report  -------------\\"<<endl;
    cout<<"ID Report: "<<ID_ReportAvis<<endl;
    cout<<"Reporter par: "<<Nom_Locataire<<endl;
    cout<<"Le: "<<Date<<endl;
    cout<<"Raison :"<<Message<<endl;
    cout<<"Statut:";
    Afficher_Statut();

    avis->Afficher_Avis_Complet();
    cout<<"\\----------------   ----------------/"<<endl;
    pause();
}

void ReportAvis::Afficher_Statut()
{
    if(Statut==0) cout<<"En cours d'examen"<<endl;
    else cout<<"Résolu"<<endl;
}
