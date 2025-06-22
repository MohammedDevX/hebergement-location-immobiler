import java.util.ArrayList;
import java.util.List;
import java.util.Date;

public class avis {
    // Attributes
    private int id;
    private String commentaire;
    private int note;
    private Date date;
    private String NomLo;
    // Relationships
    private user utilisateur;
    private annonce annonce;
    private List<avis> responses;

    // Constructors
    public avis(int id, String commentaire, int note, user utilisateur, annonce annonce) {
        this.id = id;
        this.commentaire = commentaire;
        this.note = note;
        this.date = new Date();
        this.utilisateur = utilisateur;
        this.annonce = annonce;
    }
    public avis(int id,String NomLo, int note, String commentaire, Date date) {
        this.id = id;
        this.commentaire = commentaire;
        this.note = note;
        this.date =date;
        this.NomLo=NomLo;
    }
    public avis( String commentaire, int note, user utilisateur, annonce annonce) {
        this.commentaire = commentaire;
        this.note = note;
        this.date = new Date();
        this.utilisateur = utilisateur;
        this.annonce = annonce;
        this.responses = new ArrayList<>();
    }

    // Methods
    public void modifier_avis(String commentaire, int note) {
        this.commentaire = commentaire;
        this.note = note;
        System.out.println("Avis modifié pour: " + this.annonce.getTitre());
    }

    public void supprimer_avis() {
        System.out.println("Avis supprimé pour: " + this.annonce.getTitre());
    }

    // Getters and Setters
    public int getId() {
        return id;
    }

    public String getCommentaire() {
        return commentaire;
    }

    public void setCommentaire(String commentaire) {
        this.commentaire = commentaire;
    }

    public int getNote() {
        return note;
    }

    public void setNote(int note) {
        this.note = note;
    }

    public Date getDate() {
        return date;
    }

    public user getuser() {
        return utilisateur;
    }

    public annonce getannonce() {
        return annonce;
    }
    
    public void afficherAvis() {
        System.out.println("--------------  Avis  --------------");
        System.out.println("ID Avis:\t" + this.id);
        System.out.println("Author:\t" + this.NomLo);
        System.out.println("Le: " + date);
        System.out.println("Rating:\t" + note + "/5");
        System.out.println("* " + commentaire + " *");
        System.out.println("-----------------  -----------------");
    }

}
