#include "Admin.h"
#include <limits>
#include <sstream>
#include "Utils.h"

Admin::Admin(const int& ID, const string& nom, const string&prenom,const string&email, const string& mdp, const string& tel)
{
    ID_Admin= ID;
    Nom= nom;
    Prenom=prenom;
    Email= email;
    MDP= mdp;
    Tel=tel;
}

Admin::~Admin()
{
}

string Admin::getNomComplet() const
{
    string N=Prenom + " " + Nom;
    return N;
}

void Admin::Afficher_Admin() const
{
    cout<<"Nom: \t\t"<<Nom<<endl;
    cout<<"Prenom: \t"<<Prenom<<endl;
    cout<<"Email: \t\t"<<Email<<endl;
    cout<<"Tel: \t\t"<<Tel<<endl;
}

bool Admin::Verifier_MDP(const string&mdp) const
{
    if(MDP==mdp){
        return true;
    }
    return false;
}

void Admin::Modifier_Nom(MYSQL* conn)
{
    string nvNom;
    cout<<"Nom current: "<<Nom<<endl;
    cout<<"Nouveau Nom (laisser vide pour annuler): ";
    cin.ignore(); // bach ila kant chi entrer \n mn 9bl t7ayadha
    getline(cin, nvNom); //bach y9ad ykhliha khawya ywta ghi entrer bla mayktb

    if (nvNom.empty()) {
        cout << "Aucune modification effectuee." << endl;
        return;
    }

    string escapedNom = EscapeString(conn, nvNom); // bach ntfadaw  SQL injection , maykharb9olnach database, raha function zadtha f Utils.h
    ostringstream query; // bach t9ad t3amr string b7ala rak tktab cout
    query << "UPDATE administrateur SET nom = '" << escapedNom << "' WHERE id_admin = " << ID_Admin << ";";

    if (mysql_query(conn, query.str().c_str()) != 0) { //query.str().c_str() katrad dik ostringstream l char* bach t9ad tkhadam f mysql_query
                                                       // mysql_query katsift requete
        cerr << "Erreur de mise à jour du nom: " << mysql_error(conn) << endl;
    } else {
        cout << "Nom est modifié avec succès dans la base de données." << endl;
        Nom=nvNom;
    }
}

void Admin::Modifier_Prenom(MYSQL* conn)
{
    string nvPrenom;
    cout<<"Prenom current: "<<Prenom<<endl;
    cout<<"Nouveau Prenom (laisser vide pour annuler): ";
    cin.ignore();
    getline(cin, nvPrenom);

    if (nvPrenom.empty()) {
        cout << "Aucune modification effectuee." << endl;
        return;
    }

    string escapedPrenom = EscapeString(conn, nvPrenom);
    ostringstream query;
    query << "UPDATE administrateur SET prenom = '" << escapedPrenom << "' WHERE id_admin = " << ID_Admin << ";";

    if (mysql_query(conn, query.str().c_str()) != 0) {
        cerr << "Erreur de mise a jour du prenom: " << mysql_error(conn) << endl;
    } else {
        cout << "Prenom est modifie avec succes." << endl;
        Prenom=nvPrenom;
    }
}

void Admin::Modifier_Email(MYSQL* conn)
{
    string nvEmail;
    cout<<"Email current: "<<Email<<endl;
    cout<<"Nouveau Email (laisser vide pour annuler): ";
    cin.ignore();
    getline(cin, nvEmail);

    if (nvEmail.empty()) {
        cout << "Aucune modification effectuee." << endl;
        return;
    }

    string escapedEmail = EscapeString(conn, nvEmail);
    ostringstream query;
    query << "UPDATE administrateur SET email = '" << escapedEmail << "' WHERE id_admin = " << ID_Admin << ";";

    if (mysql_query(conn, query.str().c_str()) != 0) {
        cerr << "Erreur de mise a jour d' email: " << mysql_error(conn) << endl;
    } else {
        cout << "Email est modifie avec succes." << endl;
        Email=nvEmail;
    }
}

void Admin::Modifier_Tel(MYSQL* conn)
{
    string nvTel;
    cout<<"Tel current: "<<Tel<<endl;
    cout<<"Nouveau Tel (laisser vide pour annuler): ";
    cin.ignore();
    getline(cin, nvTel);

    if (nvTel.empty()) {
        cout << "Aucune modification effectuee." << endl;
        return;
    }

    string escapedTel = EscapeString(conn, nvTel);
    ostringstream query;
    query << "UPDATE administrateur SET tel = '" << escapedTel << "' WHERE id_admin = " << ID_Admin << ";";

    if (mysql_query(conn, query.str().c_str()) != 0) {
        cerr << "Erreur de mise a jour d' email: " << mysql_error(conn) << endl;
    } else {
        cout << "Numero de telephone est modifie avec succes." << endl;
        Tel=nvTel;
    }
}

void Admin::Modifier_Mdp(MYSQL* conn)
{
    string nvMDP;
    while(true){
    cout<<"Nouveau Mot de passe: ";
    nvMDP=SaisirMotDePasse();

    if (nvMDP.empty()) {
        cout << "Aucune modification effectuee." << endl;
        return;
    }

    cout<<"Resaisie le nouveau mot de passe: ";
    string test=SaisirMotDePasse();
    if(test==nvMDP)break;
    else cout << "Les mots de passe ne correspondent pas. Veuillez reessayer.\n";
    }

    string escapedMDP = EscapeString(conn, nvMDP);
    ostringstream query;
    query << "UPDATE administrateur SET mot_passe = '" << escapedMDP << "' WHERE id_admin = " << ID_Admin << ";";

    if (mysql_query(conn, query.str().c_str()) != 0) {
        cerr << "Erreur de mise a jour d' email: " << mysql_error(conn) << endl;
    } else {
        cout << "Mot de passe est modifie avec succes." << endl;
        MDP=nvMDP;
    }
}

void Admin::Supprimer_Annonce(MYSQL* conn)
{
    int id;
    cout<<"Saisie l' ID de l'Annonce que vous voullez supprimer (0 pour annuler): ";
    cin>>id;

    if (id==0) {
        cout << "Aucune suppression effectuee. Annulation d'action." << endl;
        return;
    }

    ostringstream deletePhotosQuery;
    deletePhotosQuery << "DELETE FROM photos WHERE id_annonce = " << id;

    if (mysql_query(conn, deletePhotosQuery.str().c_str()) != 0) {
        cerr << "Erreur lors de la suppression des photos : " << mysql_error(conn) << endl;
        return;  // Exit early if photos deletion fails
    }

    ostringstream query;
    query << "DELETE FROM annonce WHERE id_annonce = " << id;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        cout << "Annonce supprimee avec succes !" << endl;
        ResoluReportAnnonce(conn,id);
    } else {
        cerr << "Erreur lors de la suppression de l'annonce : " << mysql_error(conn) << endl;
    }
}

void Admin::ResoluReportAnnonce(MYSQL* conn,int&id_annonce) //tbadal status dyal report trado 1 , status rah bool f database;
{
    ostringstream query;
    query << "UPDATE report_annonce SET statut=1 , id_admin = "<<ID_Admin
        <<" WHERE id_annonce = " << id_annonce;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        cout << "Reports status est changee avec succès !" << endl;
    } else {
        cerr << "Erreur lors de le changement de statut du report : " << mysql_error(conn) << endl;
    }
}

void Admin::ResoluReportAn(MYSQL* conn,int&id_report)
{
    ostringstream query;
    query << "UPDATE report_annonce SET statut=1 , id_admin = "<<ID_Admin
        <<" WHERE id_report_annonce = " << id_report;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        cout << "Report status est changee avec succes !" << endl;
    } else {
        cerr << "Erreur lors de le changement de statut du report : " << mysql_error(conn) << endl;
    }
}

void Admin::Supprimer_Avis(MYSQL* conn)
{
    int id;
    cout<<"Saisie l' ID de l'Avis que vous voullez supprimer (0 pour annuler): ";
    cin>>id;

    if (id==0) {
        cout << "Aucune suppression effectuee. Annulation d'action." << endl;
        return;
    }

    ostringstream deletePhotosQuery;
    deletePhotosQuery << "DELETE FROM photos WHERE id_avis = " << id;


    ostringstream query;
    query << "DELETE FROM avis WHERE id_avis = " << id;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        cout << "Avis est supprimee avec succes !" << endl;
        ResoluReportAvis(conn,id);
    } else {
        cerr << "Erreur lors de la suppression de l'Avis : " << mysql_error(conn) << endl;
    }
}

void Admin::ResoluReportAvis(MYSQL* conn,int&id_avis)
{
    ostringstream query;
    query << "UPDATE report_avis SET statut=1 , id_admin = "<<ID_Admin
        <<" WHERE id_avis = " << id_avis;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        cout << "Reports status est changee avec succès !" << endl;
    } else {
        cerr << "Erreur lors de le changement de statut du report : " << mysql_error(conn) << endl;
    }
}

void Admin::ResoluReportAv(MYSQL* conn,int&id_report)
{
    ostringstream query;
    query << "UPDATE report_avis SET statut=1 , id_admin = "<<ID_Admin
        <<" WHERE id_report_avis = "<< id_report;

    if (mysql_query(conn, query.str().c_str()) == 0) {
        cout << "Report status est changee avec succes !" << endl;
    } else {
        cerr << "Erreur lors de le changement de statut du report : " << mysql_error(conn) << endl;
    }
}
