#include "Hote.h"
#include "Annonce.h"
#include <sstream>

Hote::Hote(const int &ID, const string&nom, const string&prenom,const string& email, const int&num ,const int&ID_H):Locataire(ID, nom, prenom, email, num)
{
    ID_Hote=ID_H;
}

Hote::Hote(MYSQL* conn,const int &ID, const string&nom, const string&prenom,const string& email, const int&num ,const int&ID_H):Locataire(ID, nom, prenom, email, num)
{
    ID_Hote=ID_H;
    Remplir_Annonce(conn);
}

Hote::~Hote()
{
    for (size_t i = 0; i < Les_Annonces.size(); i++) {
        delete Les_Annonces[i];
    }
    Les_Annonces.clear();
    Les_Annonces.shrink_to_fit();
}

Hote::Hote(const Locataire& L,const int& ID):Locataire(L)
{
    ID_Hote=ID;
}

void Hote::Afficher_Hote() const
{
    char r;
    cout<<"--------------  Hote  --------------"<<endl;
    cout<<"ID Hote:"<<ID_Hote<<endl;
    Locataire::Afficher_info();
    cout<<"A poster "<<Les_Annonces.size()<<" Annonce(s)"<<endl;
    do{
        cout<<"Voullez vous voir les Annonces? (y/n)"<<endl;
        cin>>r;
    }while(r!='y' && r!='n');

    if(r=='y')Afficher_Les_Annonce();

    cout<<"-----------------  -----------------"<<endl;

    pause();
}

void Hote::Remplir_Annonce(MYSQL* conn)
{
    ostringstream query;
    query << "SELECT  an.id_annonce,an.titre,an.description_annonce,an.created_at,an.prix_nuit,an.adresse,an.capacite,an.type_logement,v.nom_ville "
    <<"FROM hote h join annonce an on h.id_hote=an.id_hote join ville v on an.id_ville=v.id_ville WHERE an.id_hote  =" << ID_Hote;

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

            Annonce* annonce = new Annonce(conn,id_annonce,titre,description,adresse, prix,date,type,ville,capacite);
            annonce->Set_Hote(this);
            Les_Annonces.push_back(annonce);
        }

        mysql_free_result(result);
    } else {
        cerr << "Erreur lors du chargement des annonce : " << mysql_error(conn) << endl;
    }
}

void Hote::Afficher_Les_Annonce() const
{
    for(size_t i=0; i<Les_Annonces.size();i++)
    {
        Les_Annonces[i]->Afficher_Annonce_Hote();
    }
}

Annonce* Hote::getAnnonce(size_t i)
{
    if (i < Les_Annonces.size()) {
        return Les_Annonces[i];
    }
    return nullptr;
}
