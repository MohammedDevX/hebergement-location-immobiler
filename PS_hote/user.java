import java.util.Date;
import java.util.Scanner;

public abstract class  user  {
    private int id;
    protected String nom;
    protected String prenom;
    protected String username;
    protected String email;
    protected String password;
    protected String num_tele;
    protected String photo_profil;
    protected Date date_naiss;

    public user() {

    }

    public user(String username_, String nom_, String prenom_, String email_, String password_, String num_tele_) {
        this.nom = nom_;
        this.prenom = prenom_;
        this.username = username_;
        this.email = email_;
        this.password = password_;
        this.num_tele = num_tele_;
    }
    public user(int id_ ,String username_, String nom_, String prenom_, String email_, String password_, String num_tele_) {
        this.id = id_;
        this.nom = nom_;
        this.prenom = prenom_;
        this.username = username_;
        this.email = email_;
        this.password = password_;
        this.num_tele = num_tele_;
    }

    public user( user u) {
        id = u.getIdUser();
        nom = u.getNom();
        prenom = u.getPreNom();
        username = u.getUsername();
        email = u.getEmail();
        password = u.getPassword();
        num_tele = u.getNum_tele();
        photo_profil = u.getPhoto_profil();
        date_naiss = u.getDate_naiss();
    }

 //  !!!  Maybe Be added as a method for the main app Class

    public static hote sInscrire() {
        Scanner scanner = new Scanner(System.in);

        System.out.println("=== Inscription Hôte ===");

        System.out.print("Nom: ");
        String nom = scanner.nextLine();

        if (nom.length()>3) {
            System.out.println("Nom doit contenir au moins 3 charactères !");
            return null;
        }

        System.out.print("Prenom: ");
        String prenom = scanner.nextLine();

        if (prenom.length()>3) {
            System.out.println("prenom doit contenir au moins 3 charactères !");
            return null;
        }

        System.out.print("username: ");
        String username = scanner.nextLine();

        if (username.length()>3) {
            System.out.println("username doit contenir au moins 3 charactères !");
            return null;
        }

        System.out.print("Email: ");
        String email = scanner.nextLine();


        System.out.print("Mot de passe: ");
        String motDePasse = scanner.nextLine();

        System.out.print("confirmer Mot de passe: ");
        String Confirm_motDePasse = scanner.nextLine();

        System.out.print("Téléphone: ");
        String telephone = scanner.nextLine();

        if (nom.isEmpty() || email.isEmpty() || motDePasse.isEmpty() || prenom.isEmpty() || telephone.isEmpty()|| Confirm_motDePasse.isEmpty()){
            System.out.println("Tous les champs sont obligatoires !");
            return null;
        } else if ( !(Confirm_motDePasse.equals(motDePasse))) {
            System.out.println("erreur en confimation de mot de passe !");
            return null;
        }
        hote nouvelHote = new hote(nom, prenom, username, email, motDePasse, telephone);
        //TODO : Insert in database
        System.out.println("Inscription réussie !");
        return nouvelHote;
    }

    public static  boolean se_connecter(){
        Scanner scanner = new Scanner(System.in);

        System.out.println("=== Connection Hôte ===");

        System.out.print("username: ");
        String username = scanner.nextLine();

        System.out.print("Mot de passe: ");
        String motDePasse = scanner.nextLine();

        if (username.isEmpty() || motDePasse.isEmpty())
            System.out.println("Tous les champs sont obligatoires !");
        else {
            //TODO: check in Database for username, motDePass
            //TODO: create object based on the info from database
            return true;
        }
        return false;
    }

    public boolean chang_mdp(){
        Scanner scanner = new Scanner(System.in);

        System.out.println("=== Changer le mot de passe ===");
        System.out.print("Mot de passe: ");
        String motDePasse = scanner.nextLine();

        System.out.print("Confirmation du Mot de passe: ");
        String Confirm_motDePasse = scanner.nextLine();

        if (Confirm_motDePasse.equals(motDePasse) && motDePasse.length()>=8) {
            password = motDePasse;
            return true;
        }
        else
            System.out.println("le mot de passe doit contenir au moins 8 charactères");
        return false;
    }

    @Override
    public abstract String toString();

//
//      GETTERS & SETTERS
//
    public int getIdUser() {
        return id;
    }

    public String getNom() {
        return nom;
    }
    public String getPreNom() {
        return prenom;
    }

    public String getUsername() {
        return username;
    }

    public String getEmail() {
        return email;
    }

    public String getPassword() {
        return password;
    }

    public String getNum_tele() {
        return num_tele;
    }

    public String getPhoto_profil() {
        return photo_profil;
    }

    public Date getDate_naiss() {
        return date_naiss;
    }
    public void setIdUser(int id) {
        this.id = id;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public void setPreNom(String prenom) {
        this.prenom = prenom;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public void setNum_tele(String num_tele) {
        this.num_tele = num_tele;
    }

    public void setPhoto_profil(String photo_profil) {
        this.photo_profil = photo_profil;
    }

    public void setDate_naiss(Date date_naiss) {
        this.date_naiss = date_naiss;
    }
}
