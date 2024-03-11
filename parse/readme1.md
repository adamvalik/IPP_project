Implementační dokumentace k 1. úloze do IPP 2023/2024

Jméno a příjmení: Adam Valík

Login: xvalik05


Implementace analyzátoru v souboru _parse.py_ kombinuje jednoduchou objektově orientovanou strukturu s návrhovým vzorem tovární metody. 

Hlavní běh programu ovládá instance třídy ``Parser`` a její metody. Nejdříve, při její konstrukci, proběhne kontrola argumentů při volání skriptu metodou ``Parser.argv_validate()``. Vypíše se nápověda v případě parametru ``—-help|-h`` nebo chybová hláška při nesprávném užití parametrů včetně nápovědy. Při volání skriptu bez parametrů se pak na řadu dostane metoda ``Parser.parse()`` a data na standardním vstupu se přečtou a rozdělí dle řádků, kde se očekávají jednotlivé instrukce. Dojde k odstranění komentářů a prázdných řádků a ke kontrole povinné hlavičky ``.IPPcode24``.

Následně dochází ke zpracování instrukce. Dle prvního slova na řádku, kde se očekává operační kód, třída ``InstructionFactory``, která funguje na bázi tovární metody, určí, který z osmi typů instrukce (podtřídy ``InstructionType[1-8]``) vytvořit. Tyto podtřídy v sobě uchovávají upřesňující informaci, co má analyzátor očekávat v pokračování těchto instrukcí a dědí atributy i metody jejich určující třídy ``Instruction``. 

![UML diagram tříd](./class.png)

Díky tomuto určení následuje kontrola argumentů instrukce, prvně jejich počet a následně se validují konkrétní argumenty dle očekávaných (ze seznamu ``expected_args`` uložených v samotné podtřídě). K ověření slouží regulární výrazy specifikované dle zadaných podmínek neterminálů _\<var\>, \<symb\>, \<label\>_ a _\<type\>_ v instrukční sadě jazyka _IPPcode24_.

Pakliže proběhla analýza vstupního kódu bez chyb, dojde k vytvoření XML reprezentace instrukce a případně jejich argumentů. U těch je pak odvozen jejich typ (kde např. argument ``int`` má přednost být ``type``, avšak jen u instrukce ``READ``, jinak se považuje za ``label``) a text. 

Po zpracování celého vstupu bez ukončení skriptu s chybovým kódem se pak strom elementů převede na řetězcovou notaci s patřičným kódováním metodou ``Parser.print_xml()`` a je vytisknut na standardní výstup.

