#include "MySQLConnection.h"
#include <iostream>
using namespace std;
MySQLConnection::MySQLConnection()
{
    conn = mysql_init(nullptr); // katwajd wa7d objet mysql n7tajoh bach tconnecta m3a database
    if (!mysql_real_connect(conn, "localhost", "root", "db123", "hebergement_particulier",3306, nullptr, 0)) {
        cerr << "Echec de la connexion a la base de donnees :" << mysql_error(conn) <<endl;
        cerr << "L'application ne peut pas continuer sans une connexion valide." <<endl;
        exit(EXIT_FAILURE);
    }
    else{cout << "La connexion a la base de donnees a ete etablie avec succes." << endl;}
}

MySQLConnection::~MySQLConnection()
{
    if (conn) mysql_close(conn);
}

MYSQL* MySQLConnection::get() const
{
    return conn;
}
