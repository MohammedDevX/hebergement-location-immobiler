#ifndef ADMIN_H
#define ADMIN_H
#include "MySQLConnection.h"
#include<string>
#include<iostream>
using namespace std;
class Admin
{
    public:
        Admin(const int& ID = 0, const string& nom= "A", const string&prenom="A", const string&email = "A@A", const string& mdp= "123",const string& tel="0600000000");
        ~Admin();
        string getNomComplet() const;
        void Afficher_Admin() const;
        bool Verifier_MDP(const string&mdp) const ;
        void Modifier_Nom(MYSQL* conn);
        void Modifier_Prenom(MYSQL* conn);
        void Modifier_Email(MYSQL* conn);
        void Modifier_Tel(MYSQL* conn);
        void Modifier_Mdp(MYSQL* conn);
        void Supprimer_Annonce(MYSQL* conn);
        void ResoluReportAn(MYSQL* conn,int&id);
        void ResoluReportAnnonce(MYSQL* conn,int&id_annonce);
        void Supprimer_Avis(MYSQL* conn);
        void ResoluReportAvis(MYSQL* conn,int&id_avis);
        void ResoluReportAv(MYSQL* conn,int&id_avis);

    private:
        int ID_Admin;
        string Nom;
        string Prenom;
        string Email;
        string MDP;
        string Tel;
};

#endif // ADMIN_H
