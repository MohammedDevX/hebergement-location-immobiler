#include "Avis.h"
#include"Utils.h"
Avis::Avis(const int &ID, const int&note, const string& C, const string&date, const string &titre_annonce)
{
    ID_Avis=ID;
    Note=note;
    Commentaire=C;
    Date=date;
    Titre_Annonce=titre_annonce;
}

Avis::Avis(const int &ID, const int&note, const string& C, const string&date, const string &titre_annonce, const string &nom_author)
{
    ID_Avis=ID;
    Note=note;
    Commentaire=C;
    Date=date;
    Titre_Annonce=titre_annonce;
    Nom_Author=nom_author;
}

Avis::Avis(const int &ID, const string &nom_author, const int&note, const string& C, const string&date)
{
    ID_Avis=ID;
    Note=note;
    Commentaire=C;
    Date=date;
    Nom_Author=nom_author;
}


Avis::~Avis()
{

}

void Avis::Modifier_Avis(const string& Comm) {
        Commentaire = Comm;
    }

void Avis::Afficher_Avis_Complet() const
{
    cout<<"--------------  Avis  --------------"<<endl;
     cout<<"ID Avis: \t"<<ID_Avis<<endl;
    cout<<"Author: \t"<<Nom_Author<<endl;
    cout<<"Titre d'Annonce: \t"<<Titre_Annonce<<endl;
    Afficher_Av();
}

void Avis::Afficher_Av() const
{
    cout<<"Le: "<<Date<<endl;
    cout<<"Rating: \t"<<Note<<"/5"<<endl;
    cout<<"* "<<Commentaire<<" *"<<endl;
    cout<<"-----------------  -----------------"<<endl;
}

void Avis::Afficher_Avis_Author() const
{
    cout<<"--------------  Avis  --------------"<<endl;
    cout<<"Author: \t"<<Nom_Author<<endl;
    Afficher_Av();
}

void Avis::Afficher_Avis_Annonce() const
{
    cout<<"--------------  Avis  --------------"<<endl;
    cout<<"Titre d'Annonce: \t"<<Titre_Annonce<<endl;
    Afficher_Av();
}

void Avis::Set_Author(const Locataire* A)
{
    Author=A;
}

void Avis::Set_Annonce(const Annonce* A)
{
    L_Annonce=A;
}

const Locataire* Avis::Get_Author()const
{
    return Author;
}

void Avis::Modifier_Commentaire(const string&C)
{
    Commentaire=C;
}
