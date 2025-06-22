#ifndef REPORTANNONCE_H
#define REPORTANNONCE_H
#include "MySQLConnection.h"
#include<string>
#include<vector>
#include<iostream>
using namespace std;

class Annonce;
class ReportAnnonce
{
    public:
        ReportAnnonce(MYSQL* conn,const int&ID_rep,const string&message,const bool&statut,const string&date,const string&nom);
        ~ReportAnnonce();
        void RemplirAnnonce(MYSQL* conn);
        void Afficher_Report();
        void Afficher_Statut();


    private:
        int ID_ReportAnnonce;
        string Message;
        bool Statut;
        string Nom_Locataire;
        Annonce* annonce;
        string Date;
};

#endif // REPORTANNONCE_H
