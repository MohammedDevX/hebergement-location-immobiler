#ifndef REPORTAVIS_H
#define REPORTAVIS_H
#include "MySQLConnection.h"
#include<string>
#include<vector>
#include<iostream>
using namespace std;

class Avis;
class ReportAvis
{
    public:
        ReportAvis(MYSQL* conn,const int&ID_rep,const string&message,const bool&statut,const string&date,const string&nom);
        ~ReportAvis();
        void RemplirAvis(MYSQL* conn);
        void Afficher_Report();
        void Afficher_Statut();


    private:
        int ID_ReportAvis;
        string Message;
        bool Statut;
        string Nom_Locataire;
        Avis* avis;
        string Date;
};

#endif // REPORTAVIS_H
