# Standard e best practices

Considerando che stiamo lavorando in tre, è importante che il codice sia scritto in modo uniforme e che sia facilmente leggibile da tutti. Per questo motivo, è necessario seguire alcune regole di stile e best practices.

Queste regole sono prese da [questo documento](https://gist.github.com/ryansechrest/8138375) e adattate per il nostro progetto. Seguitele, per favore.

## Tabella dei contenuti

1. [**Files**](#1-files)
2. [**Tags**](#2-tags)
3. [**Commenti**](#3-commenti)
4. [**Include**](#4-include)
5. [**Formattazione**](#5-formattazione)
6. [**Funzioni**](#6-funzioni)
7. [**Controlli**](#7-controlli)
8. [**Altro**](#8-altro)

## 1. Files

1. I **nomi dei file** devono essere in minuscolo
    * es. `index.php`
2. I **nomi dei file** devono essere in italiano
    * es. `utente.php`
3. Le **parole** devono essere separate da un trattino
    * es. `aggiungi-ricetta.php`

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

## 2. Tags

1. I [**tag di apertura**](#1-tag-di-apertura) devono essere sulla loro riga e devono essere seguiti da una riga vuota
2. I [**tag di chiusura**](#2-tag-di-chiusura), se il file contiene SOLO codice PHP, non dovrebbero esserci ([la documentazione di PHP](https://www.php.net/basic-syntax.phptags) spiega perché)
3. I [**tag di apertura e chiusura**](#3-tag-di-apertura-e-chiusura) nei file con HTML dovrebbero essere su una sola riga dove necessario

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

### 1. Tag di apertura

I **tag di apertura** devono essere sulla loro riga e devono essere seguiti da una riga vuota.

#### Esempio

```php
<?php

print_hello();
```

&#9650; [Tags](#2-tags)

### 2. Tag di chiusura

I **tag di chiusura**, se il file contiene SOLO codice PHP, non dovrebbero esserci ([la documentazione di PHP](https://www.php.net/basic-syntax.phptags) spiega perché).

#### Esempio

```php
<?php

print_hello();
```

&#9650; [Tags](#2-tags)

### 3. Tag di apertura e chiusura

I **tag di apertura e chiusura** nei file con HTML dovrebbero essere su una sola riga dove necessario.

#### Esempio

```html
<div>
    <h1><?php print_hello(); ?></h1>
</div>
```

&#9650; [Tags](#2-tags)

## 3. Commenti

1. I **commenti** devono essere in italiano
2. I [**commenti su singola riga**](#1-commenti-su-singola-riga) devono usare `//`
3. I [**commenti su più righe**](#2-commenti-su-più-righe) devono usare `/** */`
4. I [**commenti**](#3-commenti) devono essere **descrittivi** e **concisi**, e sulla loro riga
5. I [**blocchi di codice**](#4-blocchi-di-codice) devono essere commentati
6. Le [**variabili esterne**](#5-variabili-esterne) devono essere chiarite

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

### 1. Commenti su singola riga

I **commenti su singola riga** devono usare `//`.

#### Esempio

```php
<?php

// Questo è un commento di esempio

```

&#9650; [Commenti](#3-commenti)

### 2. Commenti su più righe

I **commenti su più righe** devono usare `/** */`.

#### Esempio

```php
<?php

/**
 * Questo è un
 * commento di esempio
 */

```

&#9650; [Commenti](#3-commenti)

### 3. Commenti

I **commenti** devono essere **descrittivi** e **concisi**, e sulla loro riga.

#### Esempio

```php
<?php

// Questa funzione stampa "Hello, world!"
echo "Hello, world!";

```

&#9650; [Commenti](#3-commenti)

### 4. Blocchi di codice

I **blocchi di codice** devono essere commentati.

#### Esempio

```php
<?php

// Effettua una ricerca nella tabella degli utenti nel database e restituisce il risultato dopo la richiesta
if (isset($_POST['ricercaBtn'])) {
    $username = $_POST['ricerca'];
    // Creazione query
    $query = "SELECT ID_utente, username, nome, cognome WHERE (username = ?)";
    // Preparazione query per esecuzione
    $statement = $mysqli->prepare($query);
    // Associazione parametri
    $statement->bind_param("s", $username);

    // ...
}

```

&#9650; [Commenti](#3-commenti)

### 5. Variabili esterne

Le **variabili esterne** devono essere chiarite.

#### Esempio

```php
<?php

include_once 'altro-file.php';

// Qui viene usata la variabile $variabile_esterna di altro-file.php
echo $variabile_esterna;

```

&#9650; [Commenti](#3-commenti)

## 4. Include

1. Bisognerebbe usare [**include/require once**](#1-include/require-once)
2. Non bisogna usare le [**parentesi**](#2-parentesi) per includere i file
3. Bisogna spiegare perché si [**sta includendo**](#3-spiegazione-include) il file

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

### 1. Include/require once

Bisognerebbe usare **include/require once**.

#### Esempio

```php
<?php

include_once 'altro-file.php';

```

&#9650; [Include](#4-include)

### 2. Parentesi

Non bisogna usare le **parentesi** per includere i file.

#### Esempio

```php
<?php

include_once 'altro-file.php';
require_once 'altro-file.php';

```

&#9650; [Include](#4-include)

### 3. Spiegazione include

Bisogna spiegare perché si sta includendo il file.

#### Esempio

```php
<?php

// Includo il file per la connessione al database
include_once 'db-connessione.php';

```

&#9650; [Include](#4-include)

## 5. Formattazione

1. Una riga non deve superare gli [**80 caratteri**](#1-80-caratteri)
2. L'[**indentazione**](#2-indentazione) deve essere con TAB
3. Le [**righe vuote**](#3-righe-vuote) devono essere usate per separare le parti del codice
4. Il testo deve essere [**allineato**](#4-allineamento) con gli spazi
5. Non devono esserci [**spazi inutili**](#5-spazi-inutili)
6. Le [**variabili**](#6-variabili) devono essere scritte in minuscolo e con trattini bassi
7. Gli [**statement**](#7-statement) devono finire con un punto e virgola
8. Gli [**operatori**](#8-operatori) devono avere spazi attorno
9. La [**concatenazione**](#9-concatenazione) deve essere fatta con i punti con gli spazi attorno
10. Le [**stringhe**](#10-stringhe) devono essere racchiuse da apici singoli
11. Nell'[**HTML**](#11-html) bisogna usare doppie virgolette per gli attributi e per i valori. Nessuno spazio tra il contenuto e l'operatore.

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

### 1. 80 caratteri

Una riga non deve superare gli **80 caratteri**.

#### Esempio

```php
<?php

// Questa riga è troppo lunga e va spezzata
$manga = array('Blue Box', 'Chainsaw Man', 'Ayakashi Triangle', 'Oniichan wa Oshimai!', 'The Great Jahy Will Not Be Defeated!', 'Kuroiwa Medaka ni Watashi no Kawaii ga Tsūjinai')

```

```php
<?php

// Questa riga è stata spezzata
$manga = array(
    'Blue Box',
    'Chainsaw Man',
    'Ayakashi Triangle',
    'Oniichan wa Oshimai!',
    'The Great Jahy Will Not Be Defeated!',
    'Kuroiwa Medaka ni Watashi no Kawaii ga Tsūjinai'
)

```

&#9650; [Formattazione](#5-formattazione)

### 2. Indentazione

L'**indentazione** deve essere con TAB.

#### Esempio

```php
<?php

// Questa riga è indentata con spazi
    echo "Hello, world!";

// Questa riga è indentata con TAB
    echo "Hello, world!";

```

&#9650; [Formattazione](#5-formattazione)

### 3. Righe vuote

Le **righe vuote** devono essere usate per separare le parti del codice.

#### Esempio

```php
<?php

// Suddivisione codice
include_once 'db-connessione.php';

$nome = 'Mario';

echo $nome;

```

&#9650; [Formattazione](#5-formattazione)

### 4. Allineamento

Il testo deve essere **allineato** con gli spazi.

#### Esempio

```php
<?php

// Questa riga non è allineata
$autori_manga = array(
    'Blue Box' => 'Kouji Miura',
    'Chainsaw Man' => 'Tatsuki Fujimoto',
    'Ayakashi Triangle' => 'Kentaro Yabuki',
    'Oniichan wa Oshimai!' => 'Nekotōfu',
    'The Great Jahy Will Not Be Defeated!' => 'Wakame Konbu',
    'Kuroiwa Medaka ni Watashi no Kawaii ga Tsūjinai' => 'Ran Kuze'
);

// Questa riga è allineata con gli spazi
$autori_manga = array(
    'Blue Box'                                        => 'Kouji Miura',
    'Chainsaw Man'                                    => 'Tatsuki Fujimoto',
    'Ayakashi Triangle'                               => 'Kentaro Yabuki',
    'Oniichan wa Oshimai!'                            => 'Nekotōfu',
    'The Great Jahy Will Not Be Defeated!'            => 'Wakame Konbu',
    'Kuroiwa Medaka ni Watashi no Kawaii ga Tsūjinai' => 'Ran Kuze'
);

```

&#9650; [Formattazione](#5-formattazione)

### 5. Spazi inutili

Non devono esserci **spazi inutili**.

#### Esempio

```php
<?php

// Questa riga ha spazi inutili alla fine
$nome = 'Mario'; 

// Questa riga non ha spazi inutili alla fine
$nome = 'Mario';

```

&#9650; [Formattazione](#5-formattazione)

### 6. Variabili

Le **variabili** devono essere scritte in minuscolo e con trattini bassi.

#### Esempio

```php
<?php

// Questa variabile è scritta correttamente
$persona_nome = 'Mario';

```

&#9650; [Formattazione](#5-formattazione)

### 7. Statement

Gli **statement** devono finire con un punto e virgola (vale anche per il codice combinato con HTML).

#### Esempio

```php
<?php

// Questo statement non ha il punto e virgola
$nome = 'Mario'

// Questo statement ha il punto e virgola
$nome = 'Mario';

```

&#9650; [Formattazione](#5-formattazione)

### 8. Operatori

Gli **operatori** devono avere spazi attorno.

#### Esempio

```php
<?php

// Questo operatore non ha spazi attorno
$nome='Mario';

// Questo operatore ha spazi attorno
$nome = 'Mario';

```

&#9650; [Formattazione](#5-formattazione)

### 9. Concatenazione

La **concatenazione** deve essere fatta con i punti con gli spazi attorno.

#### Esempio

```php
<?php

// Questa concatenazione non ha spazi attorno
echo 'Ciao, '.$nome.'!';

// Questa concatenazione ha spazi attorno
echo 'Ciao, ' . $nome . '!';

```

&#9650; [Formattazione](#5-formattazione)

### 10. Stringhe

Le **stringhe** devono essere racchiuse da apici singoli.

#### Esempio

```php
<?php

// Questa stringa non è racchiusa da apici singoli
echo "Ciao, $nome!";

// Questa stringa è racchiusa da apici singoli
echo 'Ciao, $nome!';

```

&#9650; [Formattazione](#5-formattazione)

### 11. HTML

Nell'**HTML** bisogna usare doppie virgolette per gli attributi e per i valori. Nessuno spazio tra il contenuto e l'operatore.

#### Esempio

```html
<!-- Questo attributo è racchiuso da doppie virgolette -->
<input type="text" name="nome">

<!-- Questo valore è racchiuso da doppie virgolette -->
<input type="text" name="nome" value="Mario">

<!-- Questo operatore ha spazi attorno (da non fare) -->
<input type = "text" name = "nome" value = "Mario">

```

&#9650; [Formattazione](#5-formattazione)

## 6. Funzioni

1. Le **funzioni** devono essere scritte in italiano.
2. Le [**funzioni**](#1-stile-delle-funzioni) devono essere scritte in minuscolo e con trattini bassi.
3. Gli [**argomenti**](#2-argomenti) devono:
    * Non avere uno spazio prima della virgola
    * Avere uno spazio dopo la virgola
    * Andare a capo per argomenti lunghi (tutti vanno a capo, o nessuno, con indentazione)
    * Essere ordinati per importanza e necessità
4. Le funzioni devono essere [**documentate**](#3-documentazione) correttamente ([dettagli](https://stackoverflow.com/questions/1310050/php-function-comments)).
5. Il [**return**](#4-return) deve essere preceduto da una riga vuota (e la variabile deve essere dichiarata subito).

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

### 1. Stile delle funzioni

Le **funzioni** devono essere scritte in minuscolo e con trattini bassi.

#### Esempio

```php
<?php

// Questa funzione è scritta correttamente
function nome_funzione() {
    // ...
}

```

&#9650; [Funzioni](#6-funzioni)

### 2. Argomenti

Gli **argomenti** devono:

* Non avere uno spazio prima della virgola
* Avere uno spazio dopo la virgola
* Andare a capo per argomenti lunghi (tutti vanno a capo, o nessuno, con indentazione)
* Essere ordinati per importanza e necessità

#### Esempio

```php
<?php

// Questi argomenti non hanno spazi prima e dopo la virgola
function nome_funzione($argomento1,$argomento2) {
    // ...
}

// Questi argomenti hanno spazi prima e dopo la virgola
function nome_funzione($argomento1, $argomento2) {
    // ...
}

// Questi argomenti vanno a capo
function nome_funzione(
    $argomento1,
    $argomento2
) {
    // ...
}

// Questi argomenti vanno a capo e sono ordinati per importanza e necessità

function nome_funzione(
    $argomento1,
    $argomento2,
    $argomento3 = 'default',
    $argomento4 = 'default'
) {
    // ...
}

```

&#9650; [Funzioni](#6-funzioni)

### 3. Documentazione

Le **funzioni** devono essere documentate correttamente ([dettagli](https://stackoverflow.com/questions/1310050/php-function-comments)).

#### Esempio

```php
<?php

/**
 * Questa funzione fa qualcosa.
 *
 * @access private
 * @author Alessandro Zangrandi
 * @param string $argomento1 Questo argomento è una stringa.
 * @param int    $argomento2 Questo argomento è un intero.
 * @param string $argomento3 Questo argomento è una stringa.
 * @param string $argomento4 Questo argomento è una stringa.
 * @return string Questa funzione ritorna una stringa.
 */
function nome_funzione(
    $argomento1,
    $argomento2,
    $argomento3 = 'default',
    $argomento4 = 'default'
) {
    // ...
    return 'stringa';
}

```

&#9650; [Funzioni](#6-funzioni)

### 4. Return

Il **return** deve essere preceduto da una riga vuota (e la variabile deve essere dichiarata subito).

#### Esempio

```php
<?php

/**
 * Questa funzione fa qualcosa.
 *
 * @access private
 * @author Alessandro Zangrandi
 * @param string $argomento1 Questo argomento è una stringa.
 * @param int    $argomento2 Questo argomento è un intero.
 * @param string $argomento3 Questo argomento è una stringa.
 * @param string $argomento4 Questo argomento è una stringa.
 * @return string Questa funzione ritorna una stringa.
 */
function nome_funzione(
    $argomento1,
    $argomento2,
    $argomento3 = 'default',
    $argomento4 = 'default'
) {
    // ...

    $stringa = 'stringa';

    return $stringa;
}

```

&#9650; [Funzioni](#6-funzioni)

## 7. Controlli

Queste sono alcune linee guida generali:

* Le **parole chiave** devono essere seguite da uno spazio.
  * es. `if (`, `switch (`
* Le **parentesi** d'apertura e chiusura non devono avere spazi.
  * es. `if ($condizione)`
* Le **parentesi graffe** d'apertura devono essere sulla stessa riga, mentre quelle di chiusura su una nuova riga.
* Il contenuto del controllo deve essere **indentato**.
* Non fate troppi controlli in un controllo (**nesting**).

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

Qui di seguito entriamo nello specifico per ogni tipo di controllo:

1. [**If, elseif, else**](#1-if-elseif-else)
2. [**Switch**](#2-switch)
3. [**While, do-while**](#3-while-do-while)
4. [**For, foreach**](#4-for-foreach)

### 1. If, elseif, else

Bisogna usare `elseif` invece di `else if`. Inoltre, `elseif` e `else` devono essere tra `{` e `}`.

#### Esempio

```php
<?php

// Questo controllo è scritto correttamente
if ($condizione) {
    // ...
} elseif ($condizione) {
    // ...
} else {
    // ...
}

```

&#9650; [Controlli](#7-controlli)

### 2. Switch

Gli statement dei case devono essere indentati una sola volta, con la logica generale divisa da `default` con una riga vuota. Il contenuto dei case e il break devono essere indentati due volte.

#### Esempio

```php
<?php

// Questo switch è scritto correttamente
switch ($variabile) {
    case 'valore1':
        // ...
        break;
    case 'valore2':
        // ...
        break;

    default:
        // ...
        break;
}

```

&#9650; [Controlli](#7-controlli)

### 3. While, do-while

#### Esempio

```php
<?php

// Questo while è scritto correttamente
while ($condizione) {
    // ...
}

// Questo do-while è scritto correttamente

do {
    // ...
} while ($condizione);

```

&#9650; [Controlli](#7-controlli)

### 4. For, foreach

#### Esempio

```php
<?php

// Questo for è scritto correttamente
for ($i = 0; $i < 10; $i++) {
    // ...
}

// Questo foreach è scritto correttamente
foreach ($array as $elemento) {
    // ...
}

```

&#9650; [Controlli](#7-controlli)

## 8. Altro

1. Le [**variabili**](#1-variabili) devono essere dichiarate il prima possibile e correttamente.
2. Non bisogna usare le variabili globali.

&#9650; [Tabella dei contenuti](#tabella-dei-contenuti)

### 1. Variabili

Le variabili devono essere dichiarate il prima possibile, e non bisogna usare le variabili globali.

#### Esempio

```php
<?php

// Questa variabile è dichiarata correttamente
$manga = array();

$manga = get_manga();

```

&#9650; [Altro](#8-altro)
