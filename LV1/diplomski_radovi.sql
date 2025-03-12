-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2025 at 09:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `radovi`
--

-- --------------------------------------------------------

--
-- Table structure for table `diplomski_radovi`
--

CREATE TABLE `diplomski_radovi` (
  `id` int(11) NOT NULL,
  `naziv_rada` varchar(255) NOT NULL,
  `tekst_rada` text NOT NULL,
  `link_rada` varchar(255) NOT NULL,
  `oib_tvrtke` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diplomski_radovi`
--

INSERT INTO `diplomski_radovi` (`id`, `naziv_rada`, `tekst_rada`, `link_rada`, `oib_tvrtke`) VALUES
(31, 'Web aplikacija kao platforma za postavljanje i korištenje kvizova', 'Izraditi Ruby on Rails web aplikaciju kao platformu za postavljanje kvizova s ciljem poticanja stjecanja novih znanja iz različitih područja. Registriranom korisniku se omogućuje postavljanje kviza, dok registracija nije nužna za pristup kvizovima i njihovo [...]', 'https://stup.ferit.hr/2023/11/20/web-aplikacija-kao-platforma-za-postavljanje-i-koristenje-kvizova/', '52685771113'),
(32, 'Izrada web oglasnika za usluge obavljanja poslova u kućanstvu', 'Potrebno je izraditi Ruby on Rails web-aplikaciju čija je svrha povezivanje ponuditelja usluga vezanih za obavljanje poslova u kućanstvu i onih koji traže pomoć pri njihovom izvršenju. Ponuditelj usluga bi samostalno postavljao oglase vezane za [...]', 'https://stup.ferit.hr/2023/11/20/izrada-web-oglasnika-za-usluge-obavljanja-poslova-u-kucanstvu/', '52685771113'),
(33, 'Izrada Shopify web trgovine namijenjene proizvođaču poljoprivrednih proizvoda', 'Potrebno je izraditi web trgovinu u platformi Shopify za klijenta koji proizvodi i prodaje poljoprivredne proizvode. Prilikom izrade teme web trgovine potrebno je koristiti HTML, CSS, JavaScript i Liquid. Za izvedbu dizajnerskog rješenja teme potrebno [...]', 'https://stup.ferit.hr/2023/11/20/izrada-shopify-web-trgovine-namijenjene-proizvodacu-poljoprivrednih-proizvoda/', '52685771113'),
(34, 'Ponuda završnih i diplomskih radova iz područja automatike 2023./2024.', 'Kao i prošlih godina, tvrtka TEO-Belišće d.o.o. nudi zainteresiranim studentima su-mentoriranje završnih i diplomskih radova iz područja automatike (industrijski procesi, automatizacija postrojenja, upravljanje strojevima, PLC i HMI/SCADA aplikacije, fieldbus komunikacija i sl.) i projektiranja za [...]', 'https://stup.ferit.hr/2023/10/30/ponuda-zavrsnih-i-diplomskih-radova-iz-podrucja-automatike-2023-2024/', '40480660548'),
(35, 'Tema za završni i diplomski rad – Uzgoj biljaka na ekološki način uz potporu web aplikacije', 'Tema je namijenjena studentima stručnog studija, preddiplomskog studija ili diplomskog studija. U današnje vrijeme javlja se sve veća potreba ljudi za bavljenje uzgojem biljaka koje su za konzumaciju, ali i onih koje imaju funkciju ukrašavanja [...]', 'https://stup.ferit.hr/2022/11/15/tema-za-zavrsni-i-diplomski-rad-uzgoj-biljaka-na-ekoloski-nacin-uz-potporu-web-aplikacije/', '47726994562'),
(36, 'Primjena platforme za e-trgovinu – Shopify', 'Istražiti prednosti i nedostatke platforme za e-trgovinu Shopify. Potrebno je opisati mogućnosti ovakve platforme vezane za proces od odabira do kupovine proizvoda. Također je potrebno radom dati rješenje sljedećeg problema: izrada skripte koja grupira proizvode [...]', 'https://stup.ferit.hr/2022/11/14/primjena-platforme-za-e-trgovinu-shopify/', '52685771113'),
(37, 'Claymorfizam kao dizajnerski trend web dizajna', 'Potrebno je istražiti područje dizajnerskog trenda pod nazivom Claymorfizam. Radom je potrebno ukazati na pravila claymorfizma koja grafički ili web developeri implementiraju u svojem radu. Koristeći alat Figma potrebno je napraviti dizajnersko rješenje za web [...]', 'https://stup.ferit.hr/2022/11/14/claymorfizam-kao-dizajnerski-trend-web-dizajna/', '52685771113'),
(38, 'Tema za završni i diplomski rad &#8211; Platforma za poticanje kuhanja zdrave hrane', 'Tema je namijenjena studentima stručnog studija, preddiplomskog studija ili diplomskog studija. Ubrzani način života i udaljavanje od prirode dovelo je do toga da se ljudi nezdravo hrane, a pretilost uobičajena pojava. Internet je prepun izvora [...]', 'https://stup.ferit.hr/2021/11/19/tema-za-zavrsni-i-diplomski-rad-platforma-za-poticanje-kuhanja-zdrave-hrane/', '47726994562'),
(39, 'Tema za završni i diplomski rad &#8211; Web aplikacija namijenjena vlasnicima pasa kao kućnih ljubimaca', 'Tema je namijenjena studentima stručnog studija, preddiplomskog studija ili diplomskog studija. Promjene u načinu života i trendovi promijenili su način razmišljanja o konceptu kućnog ljubimca. Oni su postali sastavni dio čovjekovog života pružajući bezuvjetnu naklonost [...]', 'https://stup.ferit.hr/2021/11/19/tema-za-zavrsni-i-diplomski-rad-web-aplikacija-namijenjena-vlasnicima-pasa-kao-kucnih-ljubimaca/', '47726994562'),
(40, 'Tema za završni i diplomski rad – Potpora zbrinjavanju otpada putem informatičko komunikacijske tehnologije', 'Tema je namijenjena studentima stručnog studija, preddiplomskog studija ili diplomskog studija. Zbrinjavanje otpada postaje problem čijem rješavanju se sve više posvećuju znanstveni i stručni krugovi, kao i šira javnost. Korporativna društvena odgovornost, održivost i svijest [...]', 'https://stup.ferit.hr/2021/11/19/tema-za-zavrsni-i-diplomski-rad-potpora-zbrinjavanju-otpada-putem-informaticko-komunikacijske-tehnologije/', '47726994562'),
(41, 'Javna rasvjeta u funkciji marketing aktivnosti', '(Tema je namijenjena studentima i studenticama diplomskog, preddiplomskog studija i stručnog studija)   Urbane sredine sve više prepoznaju mogućnosti korištenja javne rasvjete za promicanje svog vizualnog identiteta. Isto tako implementiranim marketinškim rješenjima se osigurava financijska [...]', 'https://stup.ferit.hr/2021/01/29/javna-rasvjeta-u-funkciji-marketing-aktivnosti/', '05128411490'),
(42, 'Ekonomska i energetska učinkovitost pametnog upravljanja javnom rasvjetom', '(Tema je namijenjena studentima i studenticama diplomskog, preddiplomskog studija i stručnog studija) Zadatak rada je analizirati tehnički i ekonomski aspekt primjene novih rješenja javne rasvjete. Napraviti usporedbu s tradicionalnim izvedbama u smislu energetske učinkovitosti te [...]', 'https://stup.ferit.hr/2021/01/29/ekonomska-i-energetska-ucinkovitost-pametnog-upravljanja-javnom-rasvjetom/', '05128411490'),
(43, 'Rasvijetljenost pješačkih prijelaza i svjetlosna signalizacija s tehničkog i ekonomskog aspekta', '(Tema je namijenjena studentima i studenticama diplomskog, preddiplomskog studija i stručnog studija) Sve intenzivniji promet i složenija prometna infrastruktura utječu na potrebu za novim rješenjima vezanim za sigurnost u prometu. Zadatak ovog rada je istražiti, [...]', 'https://stup.ferit.hr/2021/01/29/rasvijetljenost-pjesackih-prijelaza-i-svjetlosna-signalizacija-s-tehnickog-i-ekonomskog-aspekta/', '05128411490'),
(44, 'Tema za diplomski rad', 'NASLOV: Emulacija energetskog pretvarača za baterijske spremnike energije Mentor: Prof. dr. sc. Denis Pelin Sumentor: Kristijan Lolić (Rimac Automobili d.o.o.) ZADATAK: Pregled energetskih pretvarača za baterijske spremnike. Razvoj simulacijskog moela u programskom okruženju Typhoon HIL. [...]', 'https://stup.ferit.hr/2021/01/29/tema-za-diplomski-rad-2/', '70036017051'),
(45, 'Tema za diplomski rad', 'Rad na temu \"Web trgovina za prodaju sportske opreme zasnovana na sustavu personaliziranih preporuka\" sumentorirat će prof.dr.sc. Goran Martinović (FERIT) i Ivan Matozan (Inchoo).  U teorijskom dijelu diplomskog rada potrebno je analizirati i opisati izazove [...]', 'https://stup.ferit.hr/2021/01/21/tema-za-diplomski-rad/', '79343687407'),
(46, 'Tema za diplomski rad &#8211; API za aplikaciju za planiranje izleta', 'U nastavku možete naći temu i popis tehnologija za izradu diplomskog rada uz naše mentorstvo. Nakon odabira teme, slobodno nas možete kontaktirati na hr@factory.hr kako biste dobili više informacija o vašoj suradnji s mentorom.  Sljedeća [...]', 'https://stup.ferit.hr/2020/02/06/tema-za-diplomski-rad-api-za-aplikaciju-za-planiranje-izleta/', '47726994562'),
(47, 'Tema za diplomski rad &#8211; Informatičko-komunikacijske tehnologije u funkciji prodaje poljoprivrednih proizvoda', 'U nastavku možete naći temu i popis tehnologija za izradu diplomskog rada uz naše mentorstvo. Nakon odabira teme, slobodno nas možete kontaktirati na hr@factory.hr kako biste dobili više informacija o vašoj suradnji s mentorom.  Sljedeća [...]', 'https://stup.ferit.hr/2020/02/06/tema-za-diplomski-rad-informaticko-komunikacijske-tehnologije-u-funkciji-prodaje-poljoprivrednih-proizvoda/', '47726994562'),
(48, 'Teme za diplomske radove', 'MENTOR: Luka Olvitz NASLOV: Dizajniranje flyback visoke učinkovitosti pretvarača za visoke napone OPIS: Potrebno je dizajnirati flyback konverter za rad na naponima od 500 do 750VDC s učinkovitosti preko 80%. Za proračune će biti osiguran [...]', 'https://stup.ferit.hr/2020/02/04/teme-za-diplomske-radove/', '70036017051'),
(49, 'Strategije za smanjenje gubitaka u distributivnoj mreži 35 kV DP Elektroslavonije Osijek', 'Odobrenje teme diplomskog rada od strane HEP ODS-a (Elektroslavonije). Mentor na temi diplomskog rada: doc. dr. sc. Krešimir Fekete, a sumentor: dr. sc. Slaven Kaluđer.   Naslov teme: Strategije za smanjenje gubitaka u distributivnoj mreži [...]', 'https://stup.ferit.hr/2020/01/31/strategije-za-smanjenje-gubitaka-u-distributivnoj-mrezi-35-kv-dp-elektroslavonije-osijek/', '95494259952'),
(50, '3.	Projektiranje sustava dojave požara &#8211; zakonski, ekonomski i tehnički aspekti', 'Zadatak rada je analizirati važnost sustava dojave požara, zakonsku regulativu, ekonomski aspekt ugradnje sustava te na primjeru iz prakse dati prijedlog rješenja ugradnje ovakvog sustava. Tema se nudi studentima stručnog studija ili preddiplomskog studija. (mentorica: [...]', 'https://stup.ferit.hr/2020/01/29/3-projektiranje-sustava-dojave-pozara-zakonski-ekonomski-i-tehnicki-aspekti/', '05128411490'),
(51, '2.	Optimiziranje opće rasvjete radnih prostora obrazovne ustanove i usklađivanje s odgovarajućom normom', 'Zadatak rada je analizirati zatečeno stanje rasvjete u objektu obrazovne ustanove, dati smjernice za optimizaciju i poboljšanje u skladu s odgovarajućom normom. Potrebno je analizirati potrošnju električne energije postojeće rasvjete i ukazati na ekonomsku isplativost [...]', 'https://stup.ferit.hr/2020/01/29/2-optimiziranje-opce-rasvjete-radnih-prostora-obrazovne-ustanove-i-uskladivanje-s-odgovarajucom-normom/', '05128411490'),
(52, '1.	Ekonomski i tehnički aspekti sigurnosne rasvjete &#8211; označavanja i osvjetljavanja evakuacijskih puteva', 'Zadatak rada je opisati ulogu sigurnosne rasvjete, analizirati načine kojom ona minimizira rizike nesreća te na primjeru iz prakse napraviti prijedlog tehničkog rješenja i procjenu troškova. Tema se nudi studentima stručnog studija ili preddiplomskog studija. [...]', 'https://stup.ferit.hr/2020/01/29/1-ekonomski-i-tehnicki-aspekti-sigurnosne-rasvjete-oznacavanja-i-osvjetljavanja-evakuacijskih-puteva/', '05128411490'),
(53, 'Tema za diplomski u suradnji s tvrtkom Inchoo', 'Poštovani studenti, S velikim zadovoljstvom nastavljamo suradnju s FERITom, ove godine i tako što ćemo komentorirati izradu diplomskog rada sa temom: Izrada web shop aplikacije korištenjem MVC paradigme. Unaprijed se radujemo upoznati još jednog budućeg [...]', 'https://stup.ferit.hr/2020/01/14/tema-za-diplomski-rad-u-saradnji-sa-tvrtkom-inchoo/', '79343687407'),
(54, 'Diplomski radovi u suradnji s tvrtkom Institut RT-RK Osijek', 'Poštovani studenti diplomskih studija FERIT-a! Obavještavamo vas da tvrtka Institut RT-RK Osijek i ove godine u suradnji s FERIT-om nudi zanimljive teme diplomskih radova. Slijedi popis tema koje su na raspolaganju studentima za odabir ove [...]', 'https://stup.ferit.hr/2019/12/02/diplomski-radovi-u-suradnji-s-tvrtkom-institut-rt-rk-osijek-2/', '87006187287'),
(55, 'Tema za diplomski/završni rad-Uvođenje solarnih izvora energije, te rekonstrukcija rasvjete LED tehnologijom', 'Prikaz proračuna uštede električne energije uvođenjem solarnih sustava i LED tehnologijom!', 'https://stup.ferit.hr/2019/09/04/uvodenje-solarnih-izvora-energije-te-rekonstrukcija-rasvjete-led-tehnologijom/', '53535248695'),
(56, 'Teme za diplomske radove 3.dio', 'TEMA: FPGA implementacija SoC algoritma za Li-ion baterijske ćelije ZADATAK: Istražiti SoC algoritme u literaturi s naglaskom na Kalman filtriranje. Odabrani algoritam implementirati na Xilinx FPGA razvojnom sustavu i Vivado programskoj podršci te mjeriti vremena [...]', 'https://stup.ferit.hr/2019/01/28/teme-za-diplomske-radove-3-dio/', '70036017051'),
(57, 'Teme za diplomske radove 2.dio', 'TEMA: Upravljanje jednom granom trofaznog izmjenjivača sinusno pulsnom-širinskom moduluacijom pomoću razvojnog sustava Arduino ZADATAK: Pomoću razvojnog sustava Arduino osmisliti i adaptirati sustav za upravljanje jednom granom trofaznog izmjenjivača (jednofazni izmjenjivač u polumosnom spoju). Upravljanje izvesti [...]', 'https://stup.ferit.hr/2019/01/22/teme-za-diplomske-radove-2-dio/', '70036017051'),
(58, 'Indirektne metode upravljanja momentom motora', 'Diplomski rad se odrađuje u suradnji s tvrtkom Danieli Systec d.o.o. koja će omogućiti studentu korištenje opreme i softverskih paketa u periodu izrade diplomskog rada. Praktični dio  diplomskog rada odrađuje se u uredu Danieli Systec [...]', 'https://stup.ferit.hr/2019/01/21/indirektne-metode-upravljanja-momentom-motora/', '88285726558'),
(59, 'Testiranje ispravnosti rada SIMOREG tiristorskog usmjerivača', 'Diplomski rad se odrađuje u suradnji s tvrtkom Danieli Systec d.o.o. koja će omogućiti studentu korištenje opreme i softverskih paketa u periodu izrade diplomskog rada. Praktični dio  diplomskog rada odrađuje se u laboratoriju za električne [...]', 'https://stup.ferit.hr/2019/01/21/testiranje-ispravnosti-rada-simoreg-tiristorskog-usmjerivaca/', '88285726558'),
(60, 'Teme za diplomske radove', 'Dragi studenti! Naši su inženjeri osmislili nekoliko tema diplomskih radova iz nekoliko različitih područja, a čije radne naslove možete vidjeti u nastavku objave. Ukoliko vam je neka od ponuđenih tema interesantna, molimo vas da kontaktirate [...]', 'https://stup.ferit.hr/2019/01/10/teme-za-diplomske-radove-2/', '70036017051');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diplomski_radovi`
--
ALTER TABLE `diplomski_radovi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diplomski_radovi`
--
ALTER TABLE `diplomski_radovi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
