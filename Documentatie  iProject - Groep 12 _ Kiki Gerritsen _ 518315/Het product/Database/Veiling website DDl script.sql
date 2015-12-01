use master
go

drop database Eenmaalandermaal
go

/*==============================================================*/
/* Database: Eenmaalandermaal                                   */
/*==============================================================*/
create database Eenmaalandermaal
go

use Eenmaalandermaal
go

/*==============================================================*/
/* Table: Bestand		                                        */
/*==============================================================*/
create table Bestand (
   Voorwerp							int					not null,
   Filenaam							char(256)			not null,
   constraint PK_VOORWERP primary key  (Voorwerp ,filenaam),
)
go

/*==============================================================*/
/* Table: Bod													*/
/*==============================================================*/
create table Bod (
   Voorwerp							int			        not null,
   Bodbedrag						numeric(38)			not null,
   Gebruiker						char(70)			not null,
   BodDag							char(10)			not null,
   BodTijdstip						char(8)				not null

   constraint PK_VOORWERPBODBEDRAG primary key  (Voorwerp, Bodbedrag),
)
go

/*==============================================================*/
/* Table: Feedback		                                        */
/*==============================================================*/
create table Feedback (
   Voorwerp							int			        not null,
   SoortGebruiker					char(8)				not null, 
   Feedbacksoort					char(8)				not null, 
   Dag								char(10)			not null,
   Tijdstip							char(8)				not null,
   Commentaar						char(256)			null,
   
   check (SoortGebruiker in ('Koper' , 'Verkoper')),
   check (Feedbacksoort in ('Negatief' , 'Neutraal' , 'Positief')),

   constraint PK_VOORWERPSOORTGEBRUIKER primary key  (Voorwerp, SoortGebruiker),
)
go

/*==============================================================*/
/* Table: Gebruiker		                                        */
/*==============================================================*/

create table Gebruiker (
   Gebruikersnaam					char(70)			not null,
   Voornaam							char(20)			not null,
   Achternaam						char(25)			not null,
   Adresregel						char(50)			not null,
   Postcode							char(7)				not null,
   Plaatsnaam						char(30)			not null,
   Land								char(25)			not null,
   GeboorteDag						char(10)			not null,
   Mailbox							char(30)			not null,
   Wachtwoord						char(64)			not null,
   Vraag							integer				not null,
   Verkoper							char(3)				not null,
   Antwoordtekst					char(20)			not null,
   isNonActief						bit					not null,

   check (Verkoper in ('Ja', 'Nee')),
   constraint PK_GEBRUIKERSNAAM primary key  (Gebruikersnaam),
)
go



/*==============================================================*/
/* Table: Gebruikerstelefoon		                            */
/*==============================================================*/
create table Gebruikerstelefoon (
   Volgnr							int identity		not null,
   Gebruiker						char(70)			not null,
   Telefoonnummer					char(11)			not null,
   constraint PK_GEBRUIKERVOLGNR primary key  (Volgnr, Gebruiker),
)
go

/*==============================================================*/
/* Table: Rubriek		                                        */
/*==============================================================*/
create table Rubriek (
   Rubrieknummer					int identity		not null,
   Rubrieknaam						char(30)			not null,
   Rubriek							integer				null,
   Volgnr							integer				not null,						
   
   constraint PK_RUBRIEKNUMMER primary key  (Rubrieknummer),
)
go

/*==============================================================*/
/* Table: Verkoper		                                        */
/*==============================================================*/
create table Verkoper (
   Gebruiker						char(70)			not null,
   Bank								char(8)				null,
   Bankrekening						integer				null,
   ControleOptie					char(10)			not null,
   CreditCard						char(19)			null,

	constraint PK_GEBRUIKER primary key  (Gebruiker),
	check (controleOptie in ('Post', 'Creditcard')),
	check (Bankrekening IS NOT NULL OR CreditCard IS NOT NULL),
)	
	go

/*==============================================================*/
/* Table: Voorwerp		                                        */
/*==============================================================*/
create table Voorwerp (
   Voorwerpnummer					int identity		not null,
   Titel							char(5000)			not null,
   Beschrijving						char(5000)			not null,
   Startprijs						NUMERIC(10,2)				not null,
   Betalingswijze					Char(10)			not null,
   Betalinginstructie				char(256)			null,
   Plaatsnaam						char(50)			not null,
   Land								char(25)			not null,
   Looptijd							integer				not null,
   LooptijdBeginDag					char(10)			not null,
   LooptijdBeginTijdstip			char(8)				not null,
   Verzendkosten					char(5)				null,
   VerzendingsInstructie			char(256)			null,
   Verkoper							char(70)			not null,
   Koper							char(70)			null,
   LooptijdeindeDag					char(10)			not null,
   looptijdEindeTijdstip			char(8)				not null,
   VeilingGesloten					char(3)				not null,
   Verkoopprijs						NUMERIC(10,2)				null,
   
   constraint PK_VOORWERPNUMMER primary key  (Voorwerpnummer),
)
go
alter table voorwerp
alter column plaatsnaam char(50)
/*==============================================================*/
/* Table: Voorwerp in Rubriek		                            */
/*==============================================================*/
create table VoorwerpInRubriek (
   Voorwerp							int					not null,
   RubriekOpLaagsteNiveau			integer				not null,

  
   constraint PK_VOORWERPRUBRIEKOPLAAGSTENIVEAU primary key  (Voorwerp, RubriekOpLaagsteNiveau ),
)
go

/*==============================================================*/
/* Table: Vraag													*/
/*==============================================================*/
create table Vraag (
   Vraagnummer						integer				not null,
   Tekstvraag						char(256)			not null,
  
   constraint PK_VRAAGNUMMER primary key  (Vraagnummer),

)
go

/*==============================================================*/
/* Table: uitgelicht											*/
/*==============================================================*/
create table uitgelicht (
   uitgelicht								integer				not null,
)	
go

/*==============================================================*/
/* Table: Product van de Dag									*/
/*==============================================================*/
create table ProductvandeDag (
   Productvandedag							integer				not null,
)
go



alter table Bestand
		add constraint FK_BESTAND_REF_VOORWERP foreign key (voorwerp)
			references voorwerp (voorwerpnummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Bod
		add constraint FK_BOD_REF_VOORWERP foreign key (voorwerp)
			references voorwerp (voorwerpnummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Bod
		add constraint FK_BOD_REF_GEBRUIKER foreign key (Gebruiker)
			references Gebruiker (Gebruikersnaam)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Feedback
		add constraint FK_FEEDBACK_REF_VOORWERP foreign key (Voorwerp)
			references Voorwerp (Voorwerpnummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Gebruiker
		add constraint FK_GEBRUIKER_REF_VRAAG foreign key (Vraag)
			references Vraag (Vraagnummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table GebruikersTelefoon 
		add constraint FK_GEBRUIKERSTELEFOON_REF_GEBRUIKER foreign key (gebruiker)
			references Gebruiker (Gebruikersnaam)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Rubriek
		add constraint FK_RUBRIEK_REF_RUBRIEK foreign key (Rubriek)
			references Rubriek (Rubrieknummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Verkoper
		add constraint FK_VERKOPER_REF_GEBRUIKER foreign key (Gebruiker)
			references Gebruiker (Gebruikersnaam)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Voorwerp
		add constraint FK_VOORWERP_REF_VERKOPER foreign key (Verkoper)
			references Verkoper (Gebruiker)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table Voorwerp
		add constraint FK_VOORWERP_REF_GEBRUIKER foreign key (Koper)
			references Gebruiker (Gebruikersnaam)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table VoorwerpInRubriek
		add constraint FK_VOORWERPINRUBRIEK_REF_VOORWERP foreign key (voorwerp)
			references Voorwerp (Voorwerpnummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table VoorwerpInRubriek
		add constraint FK_VOORWERPINRUBRIEK_REF_RUBRIEK foreign key (RubriekoplaagsteNiveau)
			references Rubriek (Rubrieknummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table uitgelicht
		add constraint FK_UITGELICHT_REF_VOORWERP foreign key (uitgelicht)
			references Voorwerp (Voorwerpnummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

alter table ProductvandeDag
		add constraint FK_PRODUCTVANDEDAG_REF_VOORWERP foreign key (ProductvandeDag)
			references Voorwerp (Voorwerpnummer)
			on update no action
			on delete no action
		-- gegevens bezetting wordt gekoppeld aan reservering --
go

CREATE TRIGGER TR_CHECKCREDITCARD
	ON Verkoper
	AFTER INSERT
AS
BEGIN
	DECLARE @USER CHAR(20)
	SET @USER = (SELECT Gebruiker FROM INSERTED)

	IF (SELECT ControleOptie FROM INSERTED) = 'Creditcard' 
	BEGIN
		IF (SELECT CreditCard FROM INSERTED) IS NULL
		BEGIN
			UPDATE Verkoper SET ControleOptie = 'Post' WHERE Gebruiker = @USER
		END
	END
END
go


CREATE TRIGGER TR_CHECKVERKOPER
   ON  Gebruiker
   AFTER UPDATE,INSERT
AS 
BEGIN
	DECLARE @USER_NAME CHAR(20)
	SET @USER_NAME = (SELECT Gebruikersnaam FROM INSERTED)

	IF (SELECT Verkoper FROM Gebruiker WHERE Gebruikersnaam = @USER_NAME) = 'Ja'
	BEGIN
		IF (SELECT COUNT(*) FROM Verkoper WHERE Gebruiker = @USER_NAME) = 0
		BEGIN
			UPDATE Gebruiker SET Verkoper = 'Nee' WHERE Gebruikersnaam = @USER_NAME	
		END
	END
END
GO

CREATE TRIGGER TR_setVerkoper
   ON   verkoper
   AFTER UPDATE, INSERT
AS 
BEGIN
	DECLARE @USER_NAME CHAR(20)
	SET @USER_NAME = (SELECT Gebruiker FROM INSERTED)
			UPDATE Gebruiker SET Verkoper = 'ja' WHERE Gebruikersnaam = @USER_NAME	
END
GO

CREATE TRIGGER TR_IMAGEMAXVIER
   ON   bestand
   AFTER UPDATE, INSERT
AS 
BEGIN
	DECLARE @VOORWERPNUMMER INT
	DECLARE @FILENAAM CHAR(50)

	SET @VOORWERPNUMMER = (SELECT voorwerp FROM INSERTED)
	SET @FILENAAM = (SELECT filenaam from inserted)

	IF (SELECT COUNT(Voorwerp) FROM bestand WHERE Voorwerp = @VOORWERPNUMMER) > 4
	BEGIN
		DELETE FROM Bestand where Voorwerp = @VOORWERPNUMMER and filenaam = @FILENAAM	
	END	
END
GO

CREATE TRIGGER TR_BOD_VERKOPER
ON Bod
AFTER INSERT
AS
BEGIN
DECLARE @SELLER CHAR(20)
SET @SELLER = (SELECT verkoper FROM Voorwerp where voorwerpnummer = (SELECT  VOORWERP FROM INSERTED))

IF (SELECT gebruiker FROM INSERTED) = @SELLER
 BEGIN
	DELETE FROM bod WHERE  gebruiker = (SELECT  gebruiker FROM INSERTED) AND voorwerp = (SELECT voorwerp FROM INSERTED) AND Bodbedrag = (SELECT bodbedrag FROM INSERTED)
	END
END
go

CREATE TRIGGER TR_MAXDRIE_UITGELICHT
   ON   uitgelicht
   AFTER UPDATE, INSERT
AS 
BEGIN

	IF (SELECT COUNT(*) FROM uitgelicht) > 3
	BEGIN
		DELETE FROM uitgelicht where Uitgelicht = (SELECT Uitgelicht FROM INSERTED)
	END	
END
GO

CREATE TRIGGER TR_MAXDRIE_PRODUCTVANDEDAG
   ON   Productvandedag
   AFTER UPDATE, INSERT
AS 
BEGIN

	IF (SELECT COUNT(*) FROM Productvandedag) > 2
	BEGIN
		DELETE FROM Productvandedag where Productvandedag = (SELECT Productvandedag FROM INSERTED)
	END	
END
GO

/*==============================================================*/
/*Insert: Vraag													*/
/*==============================================================*/
insert into Vraag
values (1, 'Wat is het doel van het leven')
insert into Vraag
values (2, 'is Doctype Banaan niet awesome')
insert into Vraag
values (3, 'Waar ben je niet geboren')
insert into Vraag
values (4, 'Waar zit je niet op school')
insert into Vraag
values (5, 'Waar hoe je niet zo wel van')

/*==============================================================*/
/*Insert: Gebruiker												*/
/*==============================================================*/
insert into Gebruiker
values ('Menno', 'Menno', 'wanner', 'kastanjelaan', '3771jx', 'woudenberg', 'Belgie', '12:3:1995','geen flauw idee', 'wanner', 1, 'Nee', '0342491316', 1)
insert into Gebruiker
values('Pim', 'Pim', 'wanner', 'kastanjelaan', '3771jx', 'woudenberg', 'Belgie', '12:3:1995','geen flauw idee', 'wanner', 2, 'Nee', '0342491316', 0)
insert into Gebruiker
values('Mark', 'Mark', 'wanner', 'kastanjelaan', '3771jx', 'woudenberg', 'Belgie', '12:3:1995','geen flauw idee', 'wanner', 3, 'Nee', '0342491316', 0)
insert into Gebruiker
values('Benno', 'benno', 'wanner', 'kastanjelaan', '3771jx', 'woudenberg', 'Belgie', '12:3:1995','geen flauw idee', 'wanner', 4, 'Nee', '0342491316', 1)
insert into Gebruiker
values('Bernard', 'Bernard', 'wanner', 'kastanjelaan', '3771jx', 'woudenberg', 'Belgie', '12:3:1995','geen flauw idee', 'wanner', 5, 'Ja', '0342491316', 1)

/*==============================================================*/
/*Insert: Verkoper												*/
/*==============================================================*/
insert into Verkoper
values ('Menno', 'rabobank', NULL, 'Creditcard', 4)
insert into Verkoper 
values ('Pim', 'rabobank', 8, 'creditcard', NULL)
insert into Verkoper 
values ('Mark' , 'rabobank', 4, 'Creditcard', NULL)
insert into Verkoper 
values ('Benno', 'rabobank', 4, 'Creditcard', 4)
insert into Verkoper 
values('Bernard', 'rabobank', 4, 'Creditcard', 4)

/*==============================================================*/
/*Insert: Voorwerp												*/
/*==============================================================*/
insert into voorwerp
values ('Test1','Een voorbeeld', 50, 'Post', 'Geef het aan', 'New York', 'Amerika', 10, '2013-12-24', '00:15:00', '10', 'Dit zijn de verzendkosten', 'Benno', 'Menno', '2013-12-25', '00:18:00', 'Ja', 500)
insert into voorwerp
values ('Test2','Een voorbeeld', 60, 'Post', 'Geef het aan', 'Woudenberg', 'Belgie', 15, '2015-04-22', '00:15:00', '50', 'Dit zijn de verzendkosten', 'Menno', 'Benno', '2015-04-25', '00:18:00', 'Nee', 1000)
insert into voorwerp
values ('Test3','Een voorbeeld', 55, 'Creditcard', 'Geef het aan', 'Polska', 'Polen', 30, '2014-09-12', '00:10:00', '20', 'Dit zijn de verzendkosten', 'Pim', 'Mark', '2014-09-12', '00:18:00', 'Nee', 1500)
insert into voorwerp
values ('Test4','Een voorbeeld', 44, 'Post', 'Geef het aan', 'Antwerpen', 'Belgie', 20, '2014-09-12', '00:20:00', '50', 'Dit zijn de verzendkosten', 'Mark', 'Pim', '2014-09-12', '00:22:00', 'Ja', 50)
insert into voorwerp
values ('Test5','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2014-09-12', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2014-09-12', '00:20:00', 'Nee', 5)
insert into voorwerp
values ('Test6','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2013-12-24', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2013-12-25', '00:20:00', 'Nee', 5)
insert into voorwerp
values ('Test7','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2013-04-25', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2013-12-25', '00:20:00', 'Nee', 5)
insert into voorwerp
values ('Test8','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2013-08-03', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2013-08-05', '00:20:00', 'Nee', 5)
insert into voorwerp
values ('Test9','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2013-08-03', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2013-08-05', '00:20:00', 'Nee', 5)
insert into voorwerp
values ('Test10','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2013-08-03', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2013-08-05', '00:20:00', 'Nee', 5)
insert into voorwerp
values ('Test11','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2008-02-02', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2008-02-09', '00:20:00', 'Nee', 5)
insert into voorwerp
values ('Test12','Een voorbeeld', 90, 'Creditcard', 'geef het aan', 'Londen', 'Engeland', 10, '2008-02-02', '00:15:00', '20', 'Dit zijn de verzendkosten', 'Bernard', 'Menno', '2008-02-09', '00:20:00', 'Nee', 5)

/*==============================================================*/
/*Insert: Bestand												*/
/*==============================================================*/
insert into Bestand
values (1,'plaatje1.png')
insert into Bestand
values (2,'plaatje2.png')
insert into Bestand
values (3,'plaatje3.png')
insert into Bestand
values (4,'plaatje4.png')
insert into Bestand
values (5,'plaatje5.png')
insert into Bestand
values (6,'plaatje6.png')
insert into Bestand
values (7,'plaatje7.png')
insert into Bestand
values (8,'plaatje8.png')
insert into Bestand
values (9,'plaatje9.png')
insert into Bestand
values (10,'plaatje10.png')
insert into Bestand
values (11,'plaatje11.png')
insert into Bestand
values (12,'plaatje12.png')

/*==============================================================*/
/*Insert: Feedback												*/
/*==============================================================*/
insert into Feedback
values (1, 'koper', 'Negatief', '12:12:2014', '11:00:00', 'Slecht product')
insert into Feedback
values (2, 'koper', 'Positief', '09:11:2014', '00:15:00', 'Echt een aanrader ')
insert into Feedback
values (3, 'verkoper', 'Neutraal', '10:12:2014', '02:00:00', 'Het kan beter')
insert into Feedback
values (4, 'verkoper', 'negatief', '07:07:2014', '03:00:00', 'Zeer slecht kwaliteit')
insert into Feedback
values (5, 'koper', 'Positief', '11:11:2014', '01:50:00', 'Heel goed voor die prijs')

/*==============================================================*/
/*Insert: Bod													*/
/*==============================================================*/
insert into Bod
values (1, 5000, 'Menno', '15:08:1900', '01:15:15')
insert into Bod
values (1, 5001, 'Benno', '15:08:1900', '01:15:15')
insert into Bod
values (1, 5002, 'Bernard', '15:08:1900', '01:15:15')
insert into Bod
values (1, 5003, 'Pim', '15:08:1900', '01:15:15')
insert into Bod
values (1, 5004, 'Mark', '15:08:1900', '01:15:15')
insert into Bod
values (2, 2000, 'Benno', '12:03:1990', '00:20:00')
insert into Bod
values (3, 20, 'Bernard', '23:03:2014', '00:30:00')
insert into Bod
values (4, 200, 'Pim', '12:09:1999', '00:00:50')
insert into Bod
values (5, 150, 'Mark', '12:11:2013', '09:20:00')

/*==============================================================*/
/*Insert: Rubriek												*/
/*==============================================================*/
insert into Rubriek
values ('autos', null, 1)
insert into Rubriek
values ('Keukengerij', null, 2)
insert into Rubriek
values ('Tuinartikelen', null, 3)
insert into Rubriek
values ('Computers', null, 4)
insert into Rubriek
values ('Audi', 1, 5)
insert into Rubriek
values ('Opel', 1, 6)
insert into Rubriek
values ('Volkswagen', 1, 7)
insert into Rubriek
values ('A3', 7, 8)
insert into Rubriek
values ('Castra', 6, 9)
insert into Rubriek
values ('Polo', 5, 10)


/*==============================================================*/
/*Insert: VoorwerpInRubriek										*/
/*==============================================================*/
insert into VoorwerpInRubriek
values (1, 8)
insert into VoorwerpInRubriek
values (2, 9)
insert into VoorwerpInRubriek
values (3, 10)
insert into VoorwerpInRubriek
values (4, 8)
insert into VoorwerpInRubriek
values (5, 9)
insert into VoorwerpInRubriek
values (6, 10)
insert into VoorwerpInRubriek
values (7, 8)
insert into VoorwerpInRubriek
values (8, 9)
insert into VoorwerpInRubriek
values (9, 10)
insert into VoorwerpInRubriek
values (10, 10)
insert into VoorwerpInRubriek
values (11, 8)
insert into VoorwerpInRubriek
values (12, 8)


/*==============================================================*/
/*Insert: Gebruikerstelefoon									*/
/*==============================================================*/
insert into Gebruikerstelefoon
values ('Menno', '0342491319')
insert into Gebruikerstelefoon
values ('Benno', '0342492318')
insert into Gebruikerstelefoon
values ('Mark', '0222491318')
insert into Gebruikerstelefoon
values ('Bernard', '0342591318')
insert into Gebruikerstelefoon
values ('Pim', '03552491318')

/*==============================================================*/
/*Insert: Highlights										*/
/*==============================================================*/

insert into uitgelicht
values (1)
insert into uitgelicht
values (2)
insert into uitgelicht
values (3)

/*==============================================================*/
/*Insert: ProductvandeDag										*/
/*==============================================================*/

insert into ProductvandeDag
values (4)
insert into ProductvandeDag
values (5)
