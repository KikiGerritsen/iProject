/* creator zijn: Corne Elshof & Peter-Paul van der Mark */

SET IDENTITY_INSERT Rubriek ON
INSERT INTO Rubriek (Rubrieknummer, Rubrieknaam, Rubriek, Volgnummer)
SELECT ID, Naam, Parent, ID FROM Categorieen
SET IDENTITY_INSERT Rubriek OFF

INSERT INTO Gebruiker (Gebruikersnaam, Voornaam, Achternaam, Adresregel_1, Adresregel_2, Postcode, Plaatsnaam, Land, GeboorteDag, Mailbox, Wachtwoord, Vraag, Antwoordtekst, Verkoper)
VALUES ('EenmaalAndermaal', 'Eenmaal', 'Andermaal', 'Ruitenberglaan 26', '', '6826CC', 'Arnhem', 'Nederland', GETDATE(), 'EenmaalAndermaal@han.nl', 'password', 1, 'Onbekend', 'Nee')

INSERT INTO Verkoper VALUES ('EenmaalAndermaal', 'Rabobank', 000000000, 'Post', NULL)

UPDATE Items SET Beschrijving = 'Geen beschrijving' WHERE Beschrijving IS NULL

SET IDENTITY_INSERT Voorwerp ON
INSERT INTO Voorwerp (Voorwerpnummer, Titel, Beschrijving, Startprijs, Betalingswijze, Betalingsinstructie, Plaatsnaam, Land, Looptijd, LooptijdBeginDag, LooptijdBeginTijdstip, Verzendkosten, Verzendinstructies, Verkoper, Koper, LooptijdEindeDag, LooptijdEindeTijdstip, VeilingGesloten, Verkoopprijs)
SELECT Nummer, Titel, Beschrijving, Prijs, 'iDeal', NULL, Locatie, Land, 5, GETDATE(), GETDATE(), NULL, NULL, 'EenmaalAndermaal', NULL, GETDATE(), GETDATE(), 0, NULL FROM Items
SET IDENTITY_INSERT Voorwerp OFF

INSERT INTO Bestand (Filenaam, Voorwerpnummer)
SELECT MAX(i.PlaatjeFile), v.Nummer FROM Illustraties i, Items v WHERE i.ItemID = v.ID GROUP BY v.Nummer

INSERT INTO VoorwerpInRubriek (Rubrieknummer_op_laagste_niveau, Voorwerpnummer)
SELECT Categorie, Nummer FROM Items

/* update voor beter functioneren*/

UPDATE Rubriek SET Rubriek = NULL WHERE Rubriek = -1

UPDATE Bestand SET Filenaam = SUBSTRING(Filenaam, 6, 255)
UPDATE Bestand SET Filenaam = CAST('img' As NVARCHAR(MAX)) + CAST (Filenaam AS NVARCHAR(MAX))
UPDATE Bestand SET Filenaam =  CAST('../Gallery/' AS NVARCHAR(MAX)) + CAST (Filenaam AS NVARCHAR(MAX))

SELECT COUNT(ItemID) as Aantal, ItemID, MAX(PlaatjeFile) FROM Illustraties GROUP BY ItemID HAVING COUNT(ItemID) > 2
SELECT ItemID, PlaatjeFile FROM Illustraties WHERE ItemID = 4423848966
SELECT MAX(V.Titel) As Titel, MAX(V.Beschrijving) As Beschrijving, V.Voorwerpnummer, MAX(B.Filenaam) As Filenaam
							FROM Voorwerp V, Bestand B
							WHERE B.Voorwerpnummer = V.Voorwerpnummer
							GROUP BY V.Voorwerpnummer

UPDATE Voorwerp SET Beschrijving = dbo.usp_clearHTMLTags(Beschrijving)
UPDATE Voorwerp SET Beschrijving = (SELECT Beschrijving FROM Items WHERE Nummer = 6923) WHERE Voorwerpnummer = 6923

SELECT * FROM Voorwerp WHERE Voorwerpnummer = 6923

/****** Object:  UserDefinedFunction [dbo].[usp_ClearHTMLTags]   ******/ 
    SET ANSI_NULLS ON 
    GO 
    SET QUOTED_IDENTIFIER ON 
    GO 
    /**************************************************************************** 
    Name of Author  :   Vishal Jharwade 
    Purpose         :   The Purpose of this function is to clean the html tags from the data. 
    ***************************************************************************************/ 
    CREATE FUNCTION [dbo].[usp_ClearHTMLTags] 
    (@String NVARCHAR(MAX)) 
     
    RETURNS NVARCHAR(MAX) 
    AS 
    BEGIN 
        DECLARE @Start INT, 
                @End INT, 
                @Length INT 
         
        WHILE CHARINDEX('<', @String) > 0 AND CHARINDEX('>', @String, CHARINDEX('<', @String)) > 0 
        BEGIN 
            SELECT  @Start  = CHARINDEX('<', @String),  
                    @End    = CHARINDEX('>', @String, CHARINDEX('<', @String)) 
            SELECT @Length = (@End - @Start) + 1 
             
            IF @Length > 0 
            BEGIN 
                SELECT @String = STUFF(@String, @Start, @Length, '') 
             END 
         END 
         
        RETURN @String 
    END 

	SELECT * FROM Items
	SELECT f.Feedbacksoort, i.VerkoperPositieveFeedback  FROM Feedback f, Items i WHERE f.Voorwerp = i.Nummer
	

	SELECT * FROM Bod
INSERT INTO Bod VALUES (1, 500, 'EenmaalAndermaal', GETDATE(), GETDATE())