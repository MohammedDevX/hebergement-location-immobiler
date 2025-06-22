#include <iostream>
#include <limits>
#include "Admin.h"
#include "Hote.h"
#include "Avis.h"
#include <mysql.h>
#include "MySQLConnection.h"
#include "Reservation.h"
#include "Annonce.h"
#include <vector>
#include "Utils.h"
#include <sstream>
#include "ReportAnnonce.h"
#include "ReportAvis.h"
#include "Locataire.h"
using namespace std;
Admin SimpleAuth(MYSQL* conn);
void AdminMenu(MYSQL* conn, Admin& admin);
void MenuModification(MYSQL* conn, Admin& admin);
void DemanderEtVerifier(MYSQL* conn, Admin& admin);
vector<ReportAnnonce> GetAllReportedAnnonces(MYSQL* conn);
void AfficherReportedAnnonce(vector<ReportAnnonce>&reportedAnnonces);
vector<ReportAvis> GetAllReportedAvis(MYSQL* conn);
void AfficherReportedAvis(vector<ReportAvis>&reportedAvis);
Locataire GetLocataireParID(MYSQL* conn,int&ID);
int main() {
    MySQLConnection A;
    Admin admin = SimpleAuth(A.get());
    //Locataire B(A.get(),1,"Smith","Alice","+987654321");
    //Hote C(A.get(),1,"Smith","Alice","tenant@example.com",987654321,1);
    //C.Afficher_Hote();
    //C.getAnnonce(2)->Supprimer_Annonce(A.get());
    //Annonce E(A.get(),1,"a","a","a",1,"a","a","a",1);
    //E.Afficher_Annonce_Hote();
    //B.Afficher_Locataire();
    //B.Afficher_Les_Avis();
    /*MYSQL* conn = mysql_init(nullptr);
    if (!mysql_real_connect(conn, "localhost", "root", "db123", "mydb",3306, nullptr, 0)) {
        cerr << "Connection failed: " << mysql_error(conn) <<endl;
        return 1;
    }
    cout << "Connected as root successfully!" << endl;
    mysql_close(conn);*/



    cout << "Bienvenue " <<admin.getNomComplet() <<endl;
    AdminMenu(A.get(), admin);

    return 0;
}


Admin SimpleAuth(MYSQL* conn) {

    string email, password;

    while (true) {
        cout << "\n=== Login ===" << endl;
        cout << "Email: ";
        cin >> email;
        cout << "Password: ";
        password=SaisirMotDePasse();

        // Fixed query execution
        string query = "SELECT id_admin,nom,prenom,email,mot_passe,tel FROM administrateur WHERE email = '" + email +
                      "' AND mot_passe = '" + password + "' LIMIT 1";

        if (mysql_query(conn, query.c_str())) {  // Fixed this line
            cerr << "Login error: " << mysql_error(conn) << endl;
            continue;
        }

        MYSQL_RES* result = mysql_store_result(conn);
        if (!result) {
            cerr << "Identifiants incorrects. Veuillez reessayer." << endl;
            continue;
        }

        int num_rows = mysql_num_rows(result);

        if (num_rows == 1) {
            MYSQL_ROW row = mysql_fetch_row(result);
            if (row) {
                cout << "Login successful!\n" << endl;

                Admin adminConnecte(
                    atoi(row[0]),  // id_admin
                    row[1] ? row[1] : "",  // nom
                    row[2] ? row[2] : "",  // prenom
                    row[3] ? row[3] : "",  // email
                    row[4] ? row[4] : "",  // mdp
                    row[5] ? row[5] : ""    // tel
                );
                mysql_free_result(result);
                return adminConnecte;
        }


        } else {
            cout << "Adresse email ou mot de passe incorrect. Veuillez ressayer." << endl;
        }
    }
}

void AdminMenu(MYSQL* conn, Admin& admin) {
    int choice;

    while (true) {
        cout << "\n===== ADMIN MENU =====" << endl;
        cout << "1. Votre Profile" << endl;
        cout << "2. Modifier Votre Profile" << endl;
        cout << "3. Consulter Les Annonces Reportee" << endl;
        cout << "4. Supprimer Une Annonce" << endl;
        cout << "5. Marker Un Report Annonce Resolu" << endl;
        cout << "6. Consulter Les Avis Reportee" << endl;
        cout << "7. Supprimer Un Avis" << endl;
        cout << "8. Marker Un Report Avis Resolu" << endl;
        cout << "9. Chercher Un Locataire par ID" << endl;
        cout << "10. Logout" << endl;
        cout << "Enter votre choix: ";

        if (!(cin >> choice)) {
            ClearInputBuffer();
            cout << "Option incorrecte. Veuillez saisir un chiffre entre 1 et 10." << endl;
            continue;
        }

        switch (choice) {
            case 1: {
                cout << "\n--- Votre Profile ---" << endl;
                admin.Afficher_Admin();
                break;
            }
            case 2: {
                cout << "\n--- Profile Modification ---" << endl;
                cout<<"Vous devez verifier votre mot de passe avant d'affectuer des modification."<<endl;
                cout<<"Saisir votre mot de passe: ";
                DemanderEtVerifier(conn,admin);
                break;
            }
            case 3: {

                cout << "\n--- Consultation des reports annonces ---" << endl;
                vector<ReportAnnonce> reports = GetAllReportedAnnonces(conn);
                AfficherReportedAnnonce(reports);
                break;
            }
            case 4: {

                cout << "\n--- Supprimer Annonce ---" << endl;
                admin.Supprimer_Annonce(conn);
                break;
            }
            case 5: {

                cout << "\n--- Marker Report Annonce Resolu ---" << endl;
                int id_report_annonce;
                cout<<"Saisir l' ID du report vous voullez le marker resolu (0 pour annuler): ";
                cin>>id_report_annonce;

                if(id_report_annonce!=0)admin.ResoluReportAn(conn,id_report_annonce);
                else cout<<"Annulation d'action." << endl;
                break;
            }
            case 6: {

                cout << "\n--- Consultation des Reports Avis ---" << endl;
                vector<ReportAvis> reports = GetAllReportedAvis(conn);
                ClearInputBuffer();
                AfficherReportedAvis(reports);
                break;
            }
            case 7: {
                cout << "\n--- Supprimer Avis ---" << endl;
                admin.Supprimer_Avis(conn);
                break;
            }
            case 8: {

                cout << "\n--- Marker Report Avis Resolu ---" << endl;
                int id_report_avis;
                cout<<"Saisir l' ID du report vous voullez le marker resolu (0 pour annuler): ";
                cin>>id_report_avis;
                ClearInputBuffer();
                if(id_report_avis!=0)admin.ResoluReportAv(conn,id_report_avis);
                else cout<<"Annulation d'action." << endl;
                break;
            }
            case 9: {

                cout << "\n--- Chercher Locataire ---" << endl;
                int id_locataire;
                cout<<"Saisir l' ID du locataire vous voullez le chercher (0 pour annuler): ";
                cin>>id_locataire;
                if(id_locataire!=0)
                {
                    Locataire A=GetLocataireParID(conn,id_locataire);
                    if(A.Get_ID()!=0)
                    A.Afficher_Locataire();
                }
                else cout<<"Annulation d'action." << endl;
                break;
            }
            case 10: {
                cout<<"\n\nA La Prochaine "<<admin.getNomComplet()<<endl;
                cout << "=== Logging out... ===" << endl;
                return;
            }
            default: {
                cout << "Option incorrecte. Veuillez saisir un chiffre entre 1 et 6." << endl;
                break;
            }
        }
    }
}

void MenuModification(MYSQL* conn, Admin& admin) {
    int choice;
    //bool boucle=true;
    while (true) {
        cout << "\n--- MENU MODIFICATION ---\n";
        cout << "1. Modifier Nom\n";
        cout << "2. Modifier Prenom\n";
        cout << "3. Modifier email\n";
        cout << "4. Modifier telephone\n";
        cout << "5. Modifier mot de passe\n";
        cout << "6. Quitter modification\n";
        cout << "Entrer votre Choix: ";

        if (!(cin >> choice)) {
            ClearInputBuffer();
            cout << "Entrée invalide. Veuillez entrer un nombre entre 1 et 6." << endl;
            continue;
        }

        switch (choice) {
            case 1:
                cout << "Modification de Nom.\n";
                admin.Modifier_Nom(conn);
                break;
            case 2:
                cout << "Modifiecation de Prenom.\n";
                admin.Modifier_Prenom(conn);
                break;
            case 3:
                cout << "Modification d' email.\n";
                admin.Modifier_Email(conn);
                break;
            case 4:
                cout << "Modification de telephone.\n";
                admin.Modifier_Tel(conn);
                break;
            case 5:
                cout << "Modification de mot de passe.\n";
                admin.Modifier_Mdp(conn);
                break;
            case 6:
                cout << "-----------------------------\n";
                return; // exits the loop & function
            default:
                cout << "Choix invalide.\n";
        }
    }
}

void DemanderEtVerifier(MYSQL* conn, Admin& admin) {
    bool verifie = false;
    char retry;
    string mdp;
    while(!verifie) {
        mdp=SaisirMotDePasse();
        verifie = admin.Verifier_MDP(mdp);

        if (verifie) {
            cout << "Mot de passe correct.\n";
            MenuModification(conn,admin);  // Allow modification
            break;
        } else {
            cout << "Mot de passe incorrect.\n";
            cout << "Voulez-vous ressayer ? (Y/N): ";
            cin >> retry;
            if(retry=='N' || retry=='n') verifie=true;
            else cout<<"Resaisir Votre mot de passe: ";
        }

    }
}

vector<ReportAnnonce> GetAllReportedAnnonces(MYSQL* conn) {
    vector<ReportAnnonce> reportedAnnonces;
    ostringstream query;

    query << "SELECT ra.id_report_annonce, ra.message, ra.statut, ra.created_at, l.prenom, l.nom "
          << "FROM locataire l "
          << "JOIN report_annonce ra ON ra.id_locataire = l.id_locataire "
          << "WHERE ra.statut= 0";

    if (mysql_query(conn, query.str().c_str())) {
        cerr << "MySQL query error: " << mysql_error(conn) << endl;
        return reportedAnnonces;
    }

    MYSQL_RES* result = mysql_store_result(conn);
    if (!result) {
        cerr << "MySQL store result error: " << mysql_error(conn) << endl;
        return reportedAnnonces;
    }


    MYSQL_ROW row;
    while ((row = mysql_fetch_row(result))) {
        int id = atoi(row[0]);
        string message = row[1] ? row[1] : "";
        bool status = (atoi(row[2]) != 0);
        string date = row[3] ? row[3] : "";
        string nom = row[4] ? row[4] : "";
        nom += " ";
        nom += row[5] ? row[5] : "";

        ReportAnnonce annonce(conn, id, message, status, date, nom);
        reportedAnnonces.push_back(annonce);
    }

    mysql_free_result(result);

    return reportedAnnonces;
}

void AfficherReportedAnnonce(vector<ReportAnnonce>&reportedAnnonces){
    cout<<"Il y a "<<reportedAnnonces.size()<<" Reports d'Annonce\n"<<endl;
    for(size_t i=0;i<reportedAnnonces.size();i++){
        reportedAnnonces[i].Afficher_Report();
    }
}

vector<ReportAvis> GetAllReportedAvis(MYSQL* conn) {
    vector<ReportAvis> reportedAvis;
    ostringstream query;

    query << "SELECT ra.id_report_avis, ra.message, ra.statut, ra.created_at, l.prenom, l.nom "
          << "FROM locataire l "
          << "JOIN report_avis ra ON ra.id_locataire = l.id_locataire "
          << "WHERE ra.statut= 0 ";

    if (mysql_query(conn, query.str().c_str())) {
        cerr << "MySQL query error: " << mysql_error(conn) << endl;
        return reportedAvis;
    }

    MYSQL_RES* result = mysql_store_result(conn);
    if (!result) {
        cerr << "MySQL store result error: " << mysql_error(conn) << endl;
        return reportedAvis;
    }


    MYSQL_ROW row;
    while ((row = mysql_fetch_row(result))) {
        int id = atoi(row[0]);
        string message = row[1] ? row[1] : "";
        bool status = (atoi(row[2]) != 0);
        string date = row[3] ? row[3] : "";
        string nom = row[4] ? row[4] : "";
        nom += " ";
        nom += row[5] ? row[5] : "";

        ReportAvis avis(conn, id, message, status, date, nom);
        reportedAvis.push_back(avis);
    }

    mysql_free_result(result);

    return reportedAvis;
}

void AfficherReportedAvis(vector<ReportAvis>&reportedAvis){
    cout<<"Il y a "<<reportedAvis.size()<<" Reports d'Avis\n"<<endl;
    for(size_t i=0;i<reportedAvis.size();i++){
        reportedAvis[i].Afficher_Report();
    }
}

Locataire GetLocataireParID(MYSQL* conn, int& ID) {
    ostringstream query;
    query << "SELECT nom, prenom, email, tel, created_at "
          << "FROM locataire WHERE id_locataire= " << ID;

    if (mysql_query(conn, query.str().c_str())) {
        cerr << "MySQL query error: " << mysql_error(conn) << endl;
        return Locataire(); // return empty Locataire
    }

    MYSQL_RES* result = mysql_store_result(conn);
    if (!result) {
        cerr << "MySQL store result error: " << mysql_error(conn) << endl;
        return Locataire(); // return empty Locataire
    }

    MYSQL_ROW row = mysql_fetch_row(result);
    if (!row) {
        mysql_free_result(result);
        cout<< "Aucun Locataire trouver avec cette ID: "<<ID<<endl;
        return Locataire(); // **return empty Locataire safely**
    }

    string nom = row[0] ? row[0] : "";
    string prenom = row[1] ? row[1] : "";
    string email = row[2] ? row[2] : "";
    int tel = row[3] ? atoi(row[3]) : 0;
    //string date_naissance = row[4] ? row[4] : "";
    string date_creation = row[4] ? row[4] : "";



    mysql_free_result(result);

    return Locataire(conn, ID, nom, prenom, email, tel, "", date_creation);
}


