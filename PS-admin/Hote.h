#ifndef HOTE_H
#define HOTE_H
#include<vector>
#include "Locataire.h"

class Annonce;

class Hote : public Locataire
{
    public:
        Hote(const int &ID=0, const string&nom="nom", const string&prenom="prenom",const string& email="Email@gmail.com", const int&num=0600000000,const int&ID_H=0);
        Hote(MYSQL* conn,const int &ID=0, const string&nom="nom", const string&prenom="prenom",const string& email="Email@gmail.com", const int&num=0600000000,const int&ID_H=0);
        Hote(const Locataire&,const int&);
        ~Hote();
        void Afficher_Hote() const;
        void Afficher_Les_Annonce() const;
        void Remplir_Annonce(MYSQL* conn);
        Annonce* getAnnonce(size_t i);

    private:
        int ID_Hote;
        vector<Annonce*> Les_Annonces;
};

#endif // HOTE_H
