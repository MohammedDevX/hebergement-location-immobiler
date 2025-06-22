#ifndef AVIS_H
#define AVIS_H

#include<string>
#include<iostream>
using namespace std;

class Locataire;
class Annonce;

class Avis
{
    public:
        Avis(const int &ID=0, const int&note=0, const string& C=" ", const string&date="01-01-2025",const string &titre_annonce=" ");
        Avis(const int &ID=0, const string &nom_author=" ",const int&note=0, const string& C=" ", const string&date="01-01-2025");
        Avis(const int &ID, const int&note, const string& C, const string&date, const string &titre_annonce, const string &nom_author);
        ~Avis();

        void Modifier_Avis(const string&);
        void Afficher_Avis_Annonce() const;
        void Afficher_Avis_Author() const;
        const Locataire* Get_Author()const;
        void Set_Author(const Locataire*);
        void Modifier_Commentaire(const string&);
        void Set_Annonce(const Annonce* A);
        void Afficher_Av() const;
        void Afficher_Avis_Complet() const;

    private:
        int ID_Avis;
        int Note;
        string Commentaire;
        string Date;
        const Locataire* Author;
        const Annonce* L_Annonce;
        string Titre_Annonce;
        string Nom_Author;
};

#endif // AVIS_H
