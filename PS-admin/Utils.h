#ifndef UTILS_H
#define UTILS_H
#include "MySQLConnection.h"

#include <string>

using namespace std;

string SaisirMotDePasse();
void pause();
string EscapeString(MYSQL* conn, const string& input);
void ClearInputBuffer();

#endif // UTILS_H
