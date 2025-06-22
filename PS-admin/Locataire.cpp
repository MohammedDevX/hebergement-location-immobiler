#include "Locataire.h"
#include "Avis.h"
#include <sstream>
#include <limits>

Locataire::Locataire(MYSQL* conn,const int &ID, const string&nom, const string&prenom,const string& email, const int&num,const string date_naissance,const string date_creation)
{
    ID_Locataire= ID;
    Nom= nom;
    Prenom= prenom;
    Email= email;
    Num_Tele = num;
    Date_Naissance=date_naissance;
    Date_Creation=date_creation;
    Remplir_Avis(conn);
}

Locataire::Locataire(const int &ID, const string&nom, const string&prenom,const string& email, const int&num)
{
    ID_Locataire= ID;
    Nom= nom;
    Prenom= prenom;
    Email= email;
    Num_Tele = num;
}

Locataire::Locataire(const Locataire& L)
{
    ID_Locataire= L.ID_Locataire;
    Nom= L.Nom;
    Prenom= L.Prenom;
    Email= L.Email;
    Num_Tele = L.Num_Tele;
    Date_Naissance=L.Date_Naissance;
    Date_Creation=L.Date_Creation;
    Les_Avis = L.Les_Avis;
}

Locataire::~Locataire()
{
    for (size_t i = 0; i < Les_Avis.size(); i++) {
        delete Les_Avis[i];
    }
    Les_Avis.clear();
    Les_Avis.shrink_to_fit();
}

void Locataire::Afficher_Locataire() const
{
    char r;
    cout<<"/-------------  Locataire  -------------\\"<<endl;
    cout<<"ID Locataire:"<<ID_Locataire<<endl;
    Afficher_info();
    cout<<"A pubiler "<<Les_Avis.size()<<" avis"<<endl;

    do{
        cout<<"Voullez vous voir les Avis? (y/n)"<<endl;
        cin>>r;
    }while(r!='y' && r!='n');
    if(r=='y') Afficher_Les_Avis();
    cout<<"\\-----------------  -----------------/"<<endl;
    ClearInputBuffer();
    pause();

}

void Locataire::Afficher_info() const
{
    cout<<"Nom: "<<Nom<<endl;
    cout<<"Prenom: "<<Prenom<<endl;
    cout<<"Email: "<<Email<<endl;
    cout<<"Numero de telephone: "<<Num_Tele<<endl;
    cout<<"Date de Naissance: "<<Date_Naissance<<endl;
    cout<<"Date de Creation: "<<Date_Creation<<endl;
}

void Locataire::Ajouter_Avis(const int ID, const int&note, const string& C, const string&date)
{
    Avis* NV_Avis= new Avis(ID,note,C,date);
    NV_Avis->Set_Author(this);
    Les_Avis.push_back(NV_Avis);
}

void Locataire::Afficher_Les_Avis() const
{
    for(size_t i=0; i<Les_Avis.size();i++)
    {
        Les_Avis[i]->Afficher_Avis_Annonce();
    }
}

void Locataire::Remplir_Avis(MYSQL* conn)
{
    ostringstream query;
    query << "SELECT av.id_avis, av.note, av.commentaire, av.created_at, an.titre FROM avis av join annonce an on av.id_annonce=an.id_annonce WHERE av.id_locataire =" << ID_Locataire;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        MYSQL_RES* result = mysql_store_result(conn);
        MYSQL_ROW row;

        while ((row = mysql_fetch_row(result))) {
            int id_avis = atoi(row[0]);
            int note = atoi(row[1]);
            string commentaire = row[2] ? row[2] : "";
            string date = row[3] ? row[3] : "";
            string titre=row[4] ? row[4] : "";
            Avis* avis = new Avis(id_avis, note, commentaire, date,titre);
            avis->Set_Author(this);
            Les_Avis.push_back(avis);
        }

        mysql_free_result(result);
    } else {
        cerr << "Erreur lors du chargement des Avis : " << mysql_error(conn) << endl;
    }
}

int Locataire::Get_ID()
{
    return ID_Locataire;
}
