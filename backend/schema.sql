CREATE DATABASE IF NOT EXISTS beadando_db CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE beadando_db;

DROP TABLE IF EXISTS kapcsol;
DROP TABLE IF EXISTS talalmany;
DROP TABLE IF EXISTS kutato;

CREATE TABLE kutato (
    fkod INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(100) NOT NULL,
    szul INT DEFAULT NULL,
    meghal INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

CREATE TABLE talalmany (
    tkod INT AUTO_INCREMENT PRIMARY KEY,
    talnev VARCHAR(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

CREATE TABLE kapcsol (
    tkod INT NOT NULL,
    fkod INT NOT NULL,
    PRIMARY KEY (tkod, fkod),
    FOREIGN KEY (tkod) REFERENCES talalmany(tkod) ON DELETE CASCADE,
    FOREIGN KEY (fkod) REFERENCES kutato(fkod) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

INSERT INTO kutato (nev, szul, meghal) VALUES
('Jedlik Ányos István', 1800, 1895),
('Bláthy Ottó Titusz', 1860, 1939),
('Zipernowsky Károly', 1853, 1942),
('Déri Miksa', 1854, 1938),
('Bánki Donát', 1859, 1922),
('Csonka János', 1852, 1939),
('Puskás Tivadar', 1844, 1893),
('Kandó Kálmán', 1869, 1931),
('Ganz Ábrahám', 1814, 1867),
('Mechwart András', 1834, 1907),
('Petzval József', 1807, 1891),
('Segner János András', 1704, 1777),
('Mihály Dénes', 1894, 1953),
('Tihanyi Kálmán', 1897, 1947),
('Bíró László', 1899, 1985),
('Gábor Dénes', 1900, 1979),
('Bay Zoltán', 1900, 1992),
('Teller Ede', 1908, 2003),
('Szilárd Leó', 1898, 1964),
('Wigner Jenő', 1902, 1995),
('Neumann János', 1903, 1957),
('Békésy György', 1899, 1972),
('Szent-Györgyi Albert', 1893, 1986),
('Kármán Tódor', 1881, 1963),
('Goldmark Péter Károly', 1906, 1977),
('Kemény János György', 1926, 1992),
('Bródy Imre', 1891, 1944),
('Just Sándor', 1874, 1937),
('Frommer Rudolf', 1868, 1936),
('Hanaman Ferenc', 1878, 1941);
