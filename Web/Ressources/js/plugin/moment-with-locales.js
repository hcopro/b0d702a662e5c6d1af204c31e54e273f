;(fonction (globale, usine) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = usine() :
    typeof définir === 'fonction' && définir.amd ? définir (usine) :
    global.moment = usine()
}(this, (function () { 'use strict';

    var hookCallback ;

    crochets de fonction() {
        return hookCallback.apply(null, arguments);
    }

    // Ceci est fait pour enregistrer la méthode appelée avec moment()
    // sans créer de dépendances circulaires.
    fonction setHookCallback(rappel) {
        hookCallback = rappel ;
    }

    fonction estTableau(entrée) {
        retour (
            instance d'entrée du tableau || Object.prototype.toString.call(input) === '[tableau d'objets]'
        );
    }

    fonction estObjet(entrée) {
        // IE8 traitera undefined et null comme objet si ce n'était pas pour
        // entrée != null
        retour (
            entrée != nulle &&
            Object.prototype.toString.call(input) === '[object Object]'
        );
    }

    fonction aProp(a, b) {
        renvoie Object.prototype.hasOwnProperty.call(a, b);
    }

    function estObjetVide(obj) {
        si (Objet.getOwnPropertyNames) {
            return Object.getOwnPropertyNames(obj).length === 0;
        } autre {
            var k;
            pour (k dans obj) {
                si (hasOwnProp(obj, k)) {
                    retourner faux ;
                }
            }
            retourner vrai ;
        }
    }

    la fonction est indéfinie (entrée) {
        entrée de retour === vide 0 ;
    }

    fonction estNombre(entrée) {
        retour (
            type d'entrée === 'nombre' || Object.prototype.toString.call(input) === '[numéro d'objet]'
        );
    }

    fonction estDate(entrée) {
        retour (instance d'entrée de Date ||Object.prototype.toString.call(input) === '[object Date]');
    }

    fonction map(arr, fn) {
        var res = [],
            je,
            arrLen = arr.longueur ;
        pour (i = 0; je < arrLen; ++i) {
            res.push(fn(arr[i], i));
        }
        retour res;
    }

    fonction étendre(a, b) {
        pour (var i dans b) {
            si (aOwnProp(b, je)) {
                un[i] = b[i] ;
            }
        }

        si (hasOwnProp(b, 'toString')) {
            a.toString = b.toString ;
        }

        si (hasOwnProp(b, 'valueOf')) {
            a.valueOf = b.valueOf ;
        }

        retourner un ;
    }

    function createUTC(input, format, locale, strict) {
        return createLocalOrUTC(input, format, locale, strict, true).utc();
    }

    function defaultParsingFlags() {
        // Nous devons cloner en profondeur cet objet.
        retour {
            vide : faux,
            jetons inutilisés : [],
            entrée inutilisée : [],
            débordement : -2,
            charsLeftOver : 0,
            nullInput : faux,
            invalidEra : null,
            invalidMonth : null,
            invalidFormat : faux,
            userInvalidated : faux,
            iso : faux,
            parsedDateParts : [],
            époque : nulle,
            méridien : nul,
            rfc2822 : faux,
            jour de la semaineNon-concordance : faux,
        } ;
    }

    fonction getParsingFlags(m) {
        si (m._pf == null) {
            m._pf = defaultParsingFlags();
        }
        retourner m._pf ;
    }

    var certains ;
    si (tableau.prototype.certains) {
        certains = Array.prototype.certains ;
    } autre {
        certains = fonction (amusant) {
            var t = Objet(ceci),
                len = t.longueur >>> 0,
                je;

            pour (je = 0; je < longueur; je++) {
                if (i in t && fun.call(this, t[i], i, t)) {
                    retourner vrai ;
                }
            }

            retourner faux ;
        } ;
    }

    la fonction est valide (m) {
        si (m._isValid == null) {
            var flags = getParsingFlags(m),
                parsedParts = some.call(flags.parsedDateParts, function (i) {
                    retourner je != null ;
                }),
                estMaintenantValide =
                    !isNaN(m._d.getTime()) &&
                    flags.overflow < 0 &&
                    !flags.empty &&
                    !flags.invalidEra &&
                    !flags.invalidMonth &&
                    !flags.invalideJour de la semaine &&
                    !flags.weekdayNon-concordance &&
                    !flags.nullEntrée &&
                    !flags.invalidFormat &&
                    !flags.userInvalidated &&
                    (!flags.meridiem || (flags.meridiem && parsedParts));

            si (m._strict) {
                estMaintenantValide =
                    est maintenant valide &&
                    flags.charsLeftOver === 0 &&
                    flags.unusedTokens.length === 0 &&
                    flags.bigHour === non défini ;
            }

            if (Object.isFrozen == null || !Object.isFrozen(m)) {
                m._isValid = isNowValid ;
            } autre {
                return isNowValid ;
            }
        }
        retourne m._isValid ;
    }

    function createInvalid(flags) {
        var m = créerUTC(NaN);
        si (drapeaux != null) {
            étendre(getParsingFlags(m), drapeaux);
        } autre {
            getParsingFlags(m).userInvalidated = vrai;
        }

        retourner m ;
    }

    // Les plugins qui ajoutent des propriétés doivent également ajouter la clé ici (valeur nulle),
    // afin que nous puissions nous cloner correctement.
    var momentProperties = (hooks.momentProperties = []),
        updateInProgress = false ;

    function copierConfig(vers, depuis) ​​{
        var je,
            soutenir,
            Val,
            momentPropertiesLen = momentProperties.longueur ;

        if (!isUndefined(from._isAMomentObject)) {
            to._isAMomentObject = from._isAMomentObject ;
        }
        si (!isUndefined(from._i)) {
            à._i = de._i ;
        }
        si (!isUndefined(from._f)) {
            à._f = de._f ;
        }
        si (!isUndefined(from._l)) {
            à._l = de._l ;
        }
        si (!isUndefined(from._strict)) {
            à._strict = de._strict;
        }
        si (!isUndefined(from._tzm)) {
            vers._tzm = de._tzm ;
        }
        si (!isUndefined(from._isUTC)) {
            à._isUTC = de._isUTC ;
        }
        si (!isUndefined(from._offset)) {
            vers._offset = depuis._offset ;
        }
        si (!isUndefined(from._pf)) {
            to._pf = getParsingFlags(from);
        }
        si (!isUndefined(from._locale)) {
            vers._locale = de._locale ;
        }

        si (momentPropertiesLen > 0) {
            for (i = 0; i < momentPropertiesLen; i++) {
                prop = momentProperties[i] ;
                val = de[prop] ;
                si (!isUndefined(val)) {
                    à[prop] = val ;
                }
            }
        }

        retourner à;
    }

    // Objet prototype de moment
    fonction Moment(config) {
        copyConfig(this, config);
        this._d = new Date(config._d != null ? config._d.getTime() : NaN);
        si (!this.isValid()) {
            this._d = nouvelle Date(NaN);
        }
        // Empêcher la boucle infinie au cas où updateOffset crée un nouveau moment
        // objets.
        si (updateInProgress === faux) {
            updateInProgress = true ;
            hooks.updateOffset(this);
            updateInProgress = false ;
        }
    }

    fonction estMoment(obj) {
        retour (
            obj instance de Moment || (obj != null && obj._isAMomentObject != null)
        );
    }

    fonction avertir(msg) {
        si (
            hooks.suppressDeprecationWarnings === faux &&
            type de console !== 'indéfini' &&
            console.warn
        ) {
            console.warn('Avertissement d'obsolescence : ' + msg);
        }
    }

    fonction obsolète (msg, fn) {
        var firstTime = true ;

        retour étendre (fonction () {
            if (hooks.deprecationHandler != null) {
                hooks.deprecationHandler(null, msg);
            }
            si (première fois) {
                var arguments = [],
                    argument,
                    je,
                    clé,
                    argLen = arguments.longueur ;
                pour (je = 0; je < argLen; je++) {
                    arg = '';
                    if (typeof arguments[i] === 'object') {
                        arg += '\n[' + je + '] ';
                        pour (saisir arguments[0]) {
                            si (hasOwnProp(arguments[0], clé)) {
                                arg += clé + ' : ' + arguments[0][clé] + ', ' ;
                            }
                        }
                        arg = arg.tranche(0, -2); // Supprime la virgule et l'espace de fin
                    } autre {
                        arg = arguments[i] ;
                    }
                    args.push(arg);
                }
                avertir(
                    msg +
                        '\nArguments : ' +
                        Array.prototype.slice.call(args).join('') +
                        '\n' +
                        nouvelle erreur().pile
                );
                firstTime = faux ;
            }
            return fn.apply(this, arguments);
        }, fn);
    }

    var dépréciations = {} ;

    function deprecateSimple(nom, msg) {
        if (hooks.deprecationHandler != null) {
            hooks.deprecationHandler(nom, msg);
        }
        if (!deprecations[nom]) {
            avertir(msg);
            dépréciations[nom] = true ;
        }
    }

    hooks.suppressDeprecationWarnings = faux ;
    hooks.deprecationHandler = null ;

    fonction estFonction(entrée) {
        retour (
            (typeof Function !== 'undefined' && input instanceof Function) ||
            Object.prototype.toString.call(input) === '[object Function]'
        );
    }

    ensemble de fonctions (config) {
        var prop, je ;
        pour (je dans la configuration) {
            si (hasOwnProp (config, je)) {
                prop = config[i] ;
                si (estFonction(prop)) {
                    this[i] = prop ;
                } autre {
                    this['_' + i] = prop ;
                }
            }
        }
        this._config = config;
        // L'analyse ordinale indulgente n'accepte qu'un nombre en plus de
        // nombre + (éventuellement) trucs provenant de _dayOfMonthOrdinalParse.
        // À FAIRE : supprimer la solution de secours "ordinalParse" dans la prochaine version majeure.
        this._dayOfMonthOrdinalParseLenient = new RegExp(
            (this._dayOfMonthOrdinalParse.source || this._ordinalParse.source) +
                '|' +
                /\d{1,2}/.source
        );
    }

    fonction mergeConfigs(parentConfig, childConfig) {
        var res = extend({}, parentConfig),
            soutenir;
        pour (prop dans childConfig) {
            si (hasOwnProp(childConfig, prop)) {
                if (isObject(parentConfig[prop]) && isObject(childConfig[prop])) {
                    res[prop] = {} ;
                    étendre(res[prop], parentConfig[prop]);
                    extend(res[prop], childConfig[prop]);
                } sinon si (configenfant[prop] != null) {
                    res[prop] = childConfig[prop] ;
                } autre {
                    supprimer res[prop] ;
                }
            }
        }
        pour (prop dans parentConfig) {
            si (
                hasOwnProp(parentConfig, prop) &&
                !hasOwnProp(childConfig, prop) &&
                isObject(parentConfig[prop])
            ) {
                // assurez-vous que les modifications apportées aux propriétés ne modifient pas la configuration parente
                res[prop] = extend({}, res[prop]);
            }
        }
        retour res;
    }

    fonction Paramètres régionaux (config) {
        si (config != null) {
            this.set(config);
        }
    }

    clés var ;

    si (Objet.clés) {
        keys = Objet.keys;
    } autre {
        touches = fonction (obj) {
            var je,
                rés = [] ;
            pour (je dans obj) {
                si (hasOwnProp(obj, je)) {
                    res.push(i);
                }
            }
            retour res;
        } ;
    }

    var defaultCalendar = {
        sameDay : '[Aujourd'hui à] LT',
        nextDay : '[Demain à] LT',
        nextWeek : 'jjjj [at] LT',
        lastDay : '[Hier à] LT',
        lastWeek : '[Dernier] jjjj [at] LT',
        idemElse: 'L',
    } ;

    fonction calendrier (clé, maman, maintenant) {
        var sortie = this._calendar[clé] || this._calendar['sameElse'] ;
        return isFunction(output) ? output.call(maman, maintenant) : output;
    }

    function zeroFill(number, targetLength, forceSign) {
        var nombreabs = '' + Math.abs(nombre),
            zerosToFill = targetLength - absNumber.length,
            signe = nombre >= 0 ;
        retour (
            (signe ? (forceSign ? '+' : '') : '-') +
            Math.pow(10, Math.max(0, zerosToFill)).toString().substr(1) +
            nombreabs
        );
    }

    var formattingTokens =
            /(\[[^\[]*\])|(\\)?([Hh]mm(ss)?|Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd ?d?|faire?|w[o|w]?|W[o|W]?|Qo?|N{1,5}|YYYYYY|YYYYY|YYYY|YY|y{2,4}|yo ? |gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|kk?|mm?|ss?|S{1,9}|x|X|zz ?|ZZ?|.)/g,
        localFormattingTokens = /(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,
        formatFunctions = {},
        formatTokenFunctions = {} ;

    // jeton : 'M'
    // rembourré : ['MM', 2]
    // ordinal : 'Mo'
    // rappel : fonction () { ce.mois () + 1 }
    function addFormatToken(token, padded, ordinal, callback) {
        var fonction = rappel ;
        si (type de rappel === 'chaîne') {
            fonction = fonction () {
                retourne this[callback]();
            } ;
        }
        si (jeton) {
            formatTokenFunctions[jeton] = func ;
        }
        si (rembourré) {
            formatTokenFunctions[rembourré[0]] = fonction () {
                return zeroFill(func.apply(this, arguments), padded[1], padded[2]);
            } ;
        }
        si (ordinal) {
            formatTokenFunctions[ordinal] = fonction () {
                renvoie this.localeData().ordinal(
                    func.apply(this, arguments),
                    jeton
                );
            } ;
        }
    }

    fonction removeFormattingTokens (entrée) {
        if (input.match(/\[[\s\S]/)) {
            return input.replace(/^\[|\]$/g, '');
        }
        return input.replace(/\\/g, '');
    }

    function makeFormatFunction(format) {
        var array = format.match(formattingTokens),
            je,
            longueur;

        for (i = 0, length = array.length; i < length; i++) {
            si (formatTokenFunctions[tableau[i]]) {
                tableau[i] = formatTokenFunctions[tableau[i]] ;
            } autre {
                array[i] = removeFormattingTokens(array[i]);
            }
        }

        fonction de retour (maman) {
            var sortie = '',
                je;
            pour (i = 0; je < longueur; i++) {
                sortie += estFonction(tableau[i])
                    ? tableau[je].appel(maman, format)
                    : tableau[i] ;
            }
            retour de sortie ;
        } ;
    }

    // formate la date en utilisant l'objet date natif
    fonction formatMoment(m, format) {
        si (!m.isValid()) {
            return m.localeData().invalidDate();
        }

        format = expandFormat(format, m.localeData());
        formatFunctions[format] =
            formatFunctions[format] || makeFormatFunction(format);

        return formatFunctions[format](m);
    }

    function expandFormat(format, paramètres régionaux) {
        var je = 5 ;

        fonction remplacerLongDateFormatTokens (entrée) {
            return locale.longDateFormat(input) || saisir;
        }

        localFormattingTokens.lastIndex = 0;
        tandis que (i >= 0 && localFormattingTokens.test(format)) {
            format = format. remplacer(
                localFormattingTokens,
                remplacerLongDateFormatTokens
            );
            localFormattingTokens.lastIndex = 0;
            je -= 1;
        }

        format de retour ;
    }

    var defaultLongDateFormat = {
        LTS : 'h:mm:ss A',
        LT : 'h:mm A',
        L : 'MM/JJ/AAAA',
        LL: 'MMMM J, AAAA',
        LLL: 'MMMM J, AAAA h:mm A',
        LLLL : 'jjjj, MMMM J, AAAA h:mm A',
    } ;

    function longDateFormat(clé) {
        var format = this._longDateFormat[clé],
            formatUpper = this._longDateFormat[key.toUpperCase()] ;

        si (format || !formatSupérieur) {
            format de retour ;
        }

        this._longDateFormat[clé] = formatUpper
            .match(formattingTokens)
            .map(fonction (tok) {
                si (
                    tok === 'MMMM' ||
                    tok === 'MM' ||
                    tok === 'DD' ||
                    tok === 'dddd'
                ) {
                    return tok.slice(1);
                }
                jeton de retour ;
            })
            .rejoindre('');

        retourne this._longDateFormat[key] ;
    }

    var defaultInvalidDate = 'Date invalide';

    fonction date invalide() {
        renvoie this._invalidDate ;
    }

    var defaultOrdinal = '%d',
        defaultDayOfMonthOrdinalParse = /\d{1,2}/ ;

    fonction ordinal(nombre) {
        return this._ordinal.replace('%d', number);
    }

    var defaultRelativeTime = {
        futur : 'en %s',
        passé : 'il y a %s',
        s : 'quelques secondes',
        ss : '%d secondes',
        m : 'une minute',
        mm : '%d minutes',
        h : 'une heure',
        hh: '%d heures',
        d : 'un jour',
        jj : '%d jours',
        w : 'une semaine',
        ww: '%d semaines',
        M : 'un mois',
        MM : '%d mois',
        y : 'un an',
        aa : '%d ans',
    } ;

    function heurerelative (nombre, sans suffixe, chaîne, est futur) {
        var sortie = this._relativeTime[string] ;
        renvoie isFunction (sortie)
            ? sortie (nombre, sans suffixe, chaîne, est futur)
            : sortie.replace(/%d/i, nombre);
    }

    fonction passéFuture(diff, sortie) {
        var format = this._relativeTime[diff > 0 ? 'futur' : 'passé'] ;
        retourne estFonction(format) ? format(sortie) : format.replace(/%s/i, sortie);
    }

    var alias = {} ;

    function addUnitAlias(unité, raccourci) {
        var lowerCase = unit.toLowerCase();
        alias[minuscule] = alias[minuscule + 's'] = alias[raccourci] = unité ;
    }

    function normalizeUnits(unités) {
        renvoie le type d'unités === 'chaîne'
            ? alias[unités] || alias[units.toLowerCase()]
            : indéfini;
    }

    function normalizeObjectUnits(inputObject) {
        var NormalizedInput = {},
            proportion normalisée,
            soutenir;

        pour (prop dans inputObject) {
            si (hasOwnProp (objet d'entrée, prop)) {
                normalizedProp = normalizeUnits(prop);
                si (normalizedProp) {
                    normalizedInput[normalizedProp] = inputObject[prop] ;
                }
            }
        }

        renvoie l'entrée normalisée ;
    }

    var priorités = {} ;

    function addUnitPriority(unité, priorité) {
        priorités[unité] = priorité ;
    }

    function getPrioritizedUnits(unitsObj) {
        var unités = [],
            tu ;
        pour (u en unitésObj) {
            si (hasOwnProp(unitsObj, u)) {
                units.push({ unité : u, priorité : priorités[u] });
            }
        }
        unités.sort(fonction (a, b) {
            renvoie a.priority - b.priority ;
        });
        unités de retour ;
    }

    function isLeapYear(year) {
        retour (année % 4 === 0 && année % 100 !== 0) || année % 400 === 0 ;
    }

    fonction plancherabs(nombre) {
        si (nombre < 0) {
            // -0 -> 0
            return Math.ceil(nombre) || 0 ;
        } autre {
            return Math.floor(nombre);
        }
    }

    function toInt(argumentForCoercion) {
        var nombrecontraint = +argumentForCoercion,
            valeur = 0 ;

        if (coercedNumber !== 0 && isFinite(coercedNumber)) {
            valeur = absFloor(coercedNumber);
        }

        valeur de retour ;
    }

    function makeGetSet(unit, keepTime) {
        fonction de retour (valeur) {
            si (valeur != null) {
                set$1(ceci, unité, valeur);
                hooks.updateOffset(this, keepTime);
                retournez ceci;
            } autre {
                return get(this, unit);
            }
        } ;
    }

    fonction obtenir (maman, unité) {
        retourner maman.isValid()
            ? maman._d['get' + (maman._isUTC ? 'UTC' : '') + unité]()
            :NaN;
    }

    ensemble de fonctions$1(maman, unité, valeur) {
        if (mom.isValid() && !isNaN(value)) {
            si (
                unité === 'Année Complète' &&
                isLeapYear(mom.year()) &&
                maman.mois() === 1 &&
                maman.date() === 29
            ) {
                valeur = toInt(valeur);
                mom._d['set' + (mom._isUTC ? 'UTC' : '') + unit](
                    valeur,
                    maman.mois(),
                    joursDansMois(valeur, maman.mois())
                );
            } autre {
                mom._d['set' + (mom._isUTC ? 'UTC' : '') + unit](value);
            }
        }
    }

    // DES MOMENTS

    function stringGet(unités) {
        unités = normalizeUnits(unités);
        si (estFonction(ceci[unités])) {
            retourne this[unités]();
        }
        retournez ceci;
    }

    fonction stringSet(unités, valeur) {
        si (type d'unités === 'objet') {
            unités = normalizeObjectUnits(unités);
            var priorisé = getPrioritizedUnits(unités),
                je,
                priorizedLen = priorisé.longueur ;
            for (i = 0; i < priorizedLen; i++) {
                this[prioritized[i].unit](units[prioritized[i].unit]);
            }
        } autre {
            unités = normalizeUnits(unités);
            si (estFonction(ceci[unités])) {
                retourne this[unités](valeur);
            }
        }
        retournez ceci;
    }

    var match1 = /\d/, // 0 - 9
        match2 = /\d\d/, // 00 - 99
        match3 = /\d{3}/, // 000 - 999
        match4 = /\d{4}/, // 0000 - 9999
        match6 = /[+-]?\d{6}/, // -999999 - 999999
        match1to2 = /\d\d?/, // 0 - 99
        match3to4 = /\d\d\d\d?/, // 999 - 9999
        match5to6 = /\d\d\d\d\d\d?/, // 99999 - 999999
        match1to3 = /\d{1,3}/, // 0 - 999
        match1to4 = /\d{1,4}/, // 0 - 9999
        match1to6 = /[+-]?\d{1,6}/, // -999999 - 999999
        matchUnsigned = /\d+/, // 0 - inf
        matchSigned = /[+-]?\d+/, // -inf - inf
        matchOffset = /Z|[+-]\d\d:?\d\d/gi, // +00:00 -00:00 +0000 -0000 ou Z
        matchShortOffset = /Z|[+-]\d\d(?::?\d\d)?/gi, // +00 -00 +00:00 -00:00 +0000 -0000 ou Z
        matchTimestamp = /[+-]?\d+(\.\d{1,3})?/, // 123456789 123456789.123
        // n'importe quel mot (ou deux) caractères ou chiffres dont deux/trois mots mois en arabe.
        // inclut le gaélique écossais à deux mots et les mois avec trait d'union
        matchWord =
            /[0-9]{0 256}['az\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFF07\uFF10-\uFFEF]{1 256}|[\u0600-\u06FF\/ ]{1 256}(\s* ?[\u0600-\u06FF]{1 256}){1,2}/i,
        expressions régulières ;

    expressions régulières = {} ;

    function addRegexToken(token, regex, strictRegex) {
        regexes[token] = isFunction(regex)
            ? expression régulière
            : fonction (isStrict, localeData) {
                  retour isStrict && strictRegex ? strictRegex : regex;
              } ;
    }

    function getParseRegexForToken(token, config) {
        if (!hasOwnProp(regexes, jeton)) {
            renvoie new RegExp(unescapeFormat(token));
        }

        renvoie les expressions régulières [jeton] (config._strict, config._locale);
    }

    // Code de http://stackoverflow.com/questions/3561493/is-there-a-regexp-escape-function-in-javascript
    function unescapeFormat(s) {
        return regexEscape(
            s
                .remplacer('\\', '')
                .remplacer(
                    /\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g,
                    fonction (apparié, p1, p2, p3, p4) {
                        retourner p1 || p2 || p3 || p4 ;
                    }
                )
        );
    }

    function regexEscape(s) {
        return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    }

    var jetons = {} ;

    fonction addParseToken (jeton, rappel) {
        var je,
            func = rappel,
            tokenLen ;
        si (type de jeton === 'chaîne') {
            jeton = [jeton] ;
        }
        si (estNuméro(rappel)) {
            func = fonction (entrée, tableau) {
                array[callback] = toInt(input);
            } ;
        }
        tokenLen = token.length;
        pour (je = 0; je < tokenLen; je++) {
            jetons[jeton[i]] = func ;
        }
    }

    fonction addWeekParseToken (jeton, rappel) {
        addParseToken (jeton, fonction (entrée, tableau, configuration, jeton) {
            config._w = config._w || {} ;
            rappel (entrée, config._w, config, jeton);
        });
    }

    function addTimeToArrayFromToken(token, input, config) {
        if (input != null && hasOwnProp(tokens, token)) {
            tokens[token](input, config._a, config, token);
        }
    }

    var ANNEE = 0,
        MOIS = 1,
        DATE = 2,
        HEURE = 3,
        MINUTES = 4,
        SECONDE = 5,
        MILLISECONDE = 6,
        SEMAINE = 7,
        JOUR DE SEMAINE = 8 ;

    fonction mod(n, x) {
        retour ((n % x) + x) % x ;
    }

    var indexOf ;

    if (Array.prototype.indexOf) {
        indexOf = Array.prototype.indexOf;
    } autre {
        indexOf = fonction (o) {
            // Je sais
            var je ;
            for (i = 0; i < this.length; ++i) {
                si (ceci[je] === o) {
                    retourner je ;
                }
            }
            retour -1 ;
        } ;
    }

    fonction joursDansMois(année,mois) {
        si (estNaN(année) || estNaN(mois)) {
            renvoie NaN ;
        }
        var modMois = mod(mois, 12);
        année += (mois - modMonth) / 12 ;
        retourner modMois === 1
            ? isLeapYear(année)
                ? 29
                : 28
            : 31 - ((modMonth % 7) % 2);
    }

    // MISE EN PAGE

    addFormatToken('M', ['MM', 2], 'Mo', function () {
        retourner ce.mois() + 1 ;
    });

    addFormatToken('MMM', 0, 0, fonction (format) {
        retourne this.localeData().monthsShort(this, format);
    });

    addFormatToken('MMMM', 0, 0, fonction (format) {
        renvoie this.localeData().months(this, format);
    });

    // alias

    addUnitAlias('mois', 'M');

    // PRIORITÉ

    addUnitPriority('mois', 8);

    // PARSAGE

    addRegexToken('M', match1to2);
    addRegexToken('MM', match1to2, match2);
    addRegexToken('MMM', fonction (isStrict, locale) {
        return locale.monthsShortRegex(isStrict);
    });
    addRegexToken('MMMM', fonction (isStrict, locale) {
        return locale.monthsRegex(isStrict);
    });

    addParseToken(['M', 'MM'], fonction (entrée, tableau) {
        tableau[MOIS] = toInt(input) - 1 ;
    });

    addParseToken(['MMM', 'MMMM'], fonction (entrée, tableau, configuration, jeton) {
        var mois = config._locale.monthsParse(input, token, config._strict);
        // si nous n'avons pas trouvé de nom de mois, marque la date comme invalide.
        si (mois != null) {
            tableau[MOIS] = mois ;
        } autre {
            getParsingFlags(config).invalidMonth = entrée ;
        }
    });

    // LOCAUX

    var defaultLocaleMonths =
            'Janvier_Février_Mars_Avril_Mai_Juin_Juillet_Août_Septembre_Octobre_Novembre_Décembre'.split(
                '_'
            ),
        defaultLocaleMonthsShort =
            'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        MOIS_EN_FORMAT = /D[oD]?(\[[^\[\]]*\]|\s)+MMMM?/,
        defaultMonthsShortRegex = matchWord,
        defaultMonthsRegex = matchWord ;

    fonction localeMois(m, format) {
        si (!m) {
            retourne estArray(ce._mois)
                ? ce._mois
                : this._months['standalone'] ;
        }
        retourne estArray(ce._mois)
            ? ce._mois[m.mois()]
            : ce._mois[
                  (this._months.isFormat || MONTHS_IN_FORMAT).test(format)
                      ? 'format'
                      : 'autonome'
              ][m.mois()] ;
    }

    function localeMonthsShort(m, format) {
        si (!m) {
            retourne estArray(this._monthsShort)
                ? ce._moisCourt
                : this._monthsShort['standalone'] ;
        }
        retourne estArray(this._monthsShort)
            ? this._monthsShort[m.mois()]
            : ce._moisCourt[
                  MOIS_EN_FORMAT.test(format) ? 'format' : 'autonome'
              ][m.mois()] ;
    }

    fonction handleStrictParse(nommois, format, strict) {
        var je,
            ii,
            maman,
            llc = monthName.toLocaleLowerCase();
        si (!this._monthsParse) {
            // ceci n'est pas utilisé
            this._monthsParse = [] ;
            this._longMonthsParse = [] ;
            this._shortMonthsParse = [] ;
            pour (je = 0; je < 12; ++i) {
                maman = créerUTC([2000, i]);
                this._shortMonthsParse[i] = this.monthsShort(
                    maman,
                    ''
                ).toLocaleLowerCase();
                this._longMonthsParse[i] = this.months(maman, '').toLocaleLowerCase();
            }
        }

        si (strict) {
            si (format === 'MMM') {
                ii = indexOf.call(this._shortMonthsParse, llc);
                retour ii !== -1 ? ii : nul ;
            } autre {
                ii = indexOf.call(this._longMonthsParse, llc);
                retour ii !== -1 ? ii : nul ;
            }
        } autre {
            si (format === 'MMM') {
                ii = indexOf.call(this._shortMonthsParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._longMonthsParse, llc);
                retour ii !== -1 ? ii : nul ;
            } autre {
                ii = indexOf.call(this._longMonthsParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._shortMonthsParse, llc);
                retour ii !== -1 ? ii : nul ;
            }
        }
    }

    function localeMonthsParse(monthName, format, strict) {
        var je, maman, regex ;

        si (this._monthsParseExact) {
            return handleStrictParse.call(this, monthName, format, strict);
        }

        si (!this._monthsParse) {
            this._monthsParse = [] ;
            this._longMonthsParse = [] ;
            this._shortMonthsParse = [] ;
        }

        // TODO : ajouter un tri
        // Le tri vérifie si un mois (ou abbr) est le préfixe d'un autre
        // voir le tri dans computeMonthsParse
        pour (je = 0; je < 12; je++) {
            // crée la regex si nous ne l'avons pas déjà
            maman = créerUTC([2000, i]);
            if (strict && !this._longMonthsParse[i]) {
                this._longMonthsParse[i] = nouvelle RegExp(
                    '^' + this.months(maman, '').replace('.', '') + '$',
                    'je'
                );
                this._shortMonthsParse[i] = nouvelle RegExp(
                    '^' + this.monthsShort(maman, '').replace('.', '') + '$',
                    'je'
                );
            }
            if (!strict && !this._monthsParse[i]) {
                expression régulière =
                    '^' + this.months(maman, '') + '|^' + this.monthsShort(maman, '');
                this._monthsParse[i] = new RegExp(regex.replace('.', ''), 'i');
            }
            // teste la regex
            si (
                strict &&
                format === 'MMMM' &&
                this._longMonthsParse[i].test(monthName)
            ) {
                retourner je ;
            } sinon si (
                strict &&
                format === 'MMM' &&
                this._shortMonthsParse[i].test(monthName)
            ) {
                retourner je ;
            } sinon si (!strict && this._monthsParse[i].test(monthName)) {
                retourner je ;
            }
        }
    }

    // DES MOMENTS

    fonction setMonth(maman, valeur) {
        var jourDuMois ;

        si (!mom.isValid()) {
            // Pas d'opération
            retour maman;
        }

        si (type de valeur === 'chaîne') {
            si (/^\d+$/.test(valeur)) {
                valeur = toInt(valeur);
            } autre {
                valeur = mom.localeData().monthsParse(value);
                // À FAIRE : Un autre échec silencieux ?
                si (!estNombre(valeur)) {
                    retour maman;
                }
            }
        }

        dayOfMonth = Math.min(mom.date(), daysInMonth(mom.year(), value));
        mom._d['set' + (mom._isUTC ? 'UTC' : '') + 'Month'](value, dayOfMonth);
        retour maman;
    }

    fonction getSetMonth(valeur) {
        si (valeur != null) {
            setMonth(this, value);
            hooks.updateOffset(this, true);
            retournez ceci;
        } autre {
            return get(this, 'Mois');
        }
    }

    function getDaysInMonth() {
        return daysInMonth(this.year(), this.month());
    }

    fonction moisCourtRegex(isStrict) {
        si (this._monthsParseExact) {
            si (!hasOwnProp(this, '_monthsRegex')) {
                calculateMonthsParse.call(this);
            }
            si (estStrict) {
                renvoie ceci._monthsShortStrictRegex ;
            } autre {
                retourne this._monthsShortRegex ;
            }
        } autre {
            si (!hasOwnProp(this, '_monthsShortRegex')) {
                this._monthsShortRegex = defaultMonthsShortRegex ;
            }
            renvoie ceci._monthsShortStrictRegex && isStrict
                ? this._monthsShortStrictRegex
                : this._monthsShortRegex ;
        }
    }

    fonction moisRegex(isStrict) {
        si (this._monthsParseExact) {
            si (!hasOwnProp(this, '_monthsRegex')) {
                calculateMonthsParse.call(this);
            }
            si (estStrict) {
                renvoie this._monthsStrictRegex ;
            } autre {
                retourne this._monthsRegex ;
            }
        } autre {
            si (!hasOwnProp(this, '_monthsRegex')) {
                this._monthsRegex = defaultMonthsRegex ;
            }
            retourner ceci._monthsStrictRegex && isStrict
                ? this._monthsStrictRegex
                : this._monthsRegex;
        }
    }

    function calculateMonthsParse() {
        fonction cmpLenRev(a, b) {
            return b.length - a.length ;
        }

        var ShortPieces = [],
            longPieces = [],
            pièces mixtes = [],
            je,
            maman;
        pour (je = 0; je < 12; je++) {
            // crée la regex si nous ne l'avons pas déjà
            maman = créerUTC([2000, i]);
            shortPieces.push(this.monthsShort(maman, ''));
            longPieces.push(ce.mois(maman, ''));
            mixedPieces.push(ce.mois(maman, ''));
            mixedPieces.push(this.monthsShort(maman, ''));
        }
        // Le tri vérifie si un mois (ou abbr) est un préfixe d'un autre
        // correspondra à la pièce la plus longue.
        shortPieces.sort(cmpLenRev);
        longPieces.sort(cmpLenRev);
        mixedPieces.sort(cmpLenRev);
        pour (je = 0; je < 12; je++) {
            shortPieces[i] = regexEscape(shortPieces[i]);
            longPieces[i] = regexEscape(longPieces[i]);
        }
        pour (je = 0; je < 24; je++) {
            MixedPieces[i] = regexEscape(mixedPieces[i]);
        }

        this._monthsRegex = new RegExp('^(' + mixedPieces.join('|') + ')', 'i');
        this._monthsShortRegex = this._monthsRegex ;
        this._monthsStrictRegex = nouvelle RegExp(
            '^(' + longPieces.join('|') + ')',
            'je'
        );
        this._monthsShortStrictRegex = nouvelle RegExp(
            '^(' + shortPieces.join('|') + ')',
            'je'
        );
    }

    // MISE EN PAGE

    addFormatToken('O', 0, 0, fonction () {
        var y = this.year();
        retourner y <= 9999 ? zeroFill(y, 4) : '+' + y;
    });

    addFormatToken(0, ['AA', 2], 0, fonction () {
        return this.year() % 100;
    });

    addFormatToken(0, ['AAAA', 4], 0, 'année');
    addFormatToken(0, ['AAAA', 5], 0, 'année');
    addFormatToken(0, ['YYYYYY', 6, vrai], 0, 'année');

    // alias

    addUnitAlias('année', 'y');

    // PRIORITÉS

    addUnitPriority('année', 1);

    // PARSAGE

    addRegexToken('Y', matchSigned);
    addRegexToken('YY', match1to2, match2);
    addRegexToken('YYYY', match1to4, match4);
    addRegexToken('YYYYY', match1to6, match6);
    addRegexToken('YYYYYY', match1to6, match6);

    addParseToken(['YYYYY', 'YYYYYY'], YEAR);
    addParseToken('AAAA', fonction (entrée, tableau) {
        tableau[ANNEE] =
            entrée.longueur === 2 ? hooks.parseTwoDigitYear(entrée) : toInt(entrée);
    });
    addParseToken('YY', fonction (entrée, tableau) {
        array[YEAR] = hooks.parseTwoDigitYear(input);
    });
    addParseToken('Y', fonction (entrée, tableau) {
        array[YEAR] = parseInt(input, 10);
    });

    // AIDE

    fonction joursDansAnnée(année) {
        return isLeapYear(year) ? 366 : 365;
    }

    // CROCHETS

    hooks.parseTwoDigitYear = fonction (entrée) {
        return toInt(input) + (toInt(input) > 68 ? 1900 : 2000);
    } ;

    // DES MOMENTS

    var getSetYear = makeGetSet('FullYear', true);

    function getIsLeapYear() {
        return isLeapYear(this.year());
    }

    function créerDate(y, m, d, h, M, s, ms) {
        // ne peut pas simplement appliquer () pour créer une date :
        // https://stackoverflow.com/q/181348
        date variable ;
        // le constructeur de date remappe les années 0-99 à 1900-1999
        si (y < 100 && y >= 0) {
            // conserver les années bissextiles en utilisant un cycle complet de 400 ans, puis réinitialiser
            date = nouvelle Date(a + 400, m, j, h, M, s, ms);
            si (isFinite(date.getFullYear())) {
                date.setFullYear(y);
            }
        } autre {
            date = nouvelle Date(a, m, j, h, M, s, ms);
        }

        date de retour;
    }

    function créerUTCDate(y) {
        var date, arguments ;
        // la fonction Date.UTC remappe les années 0-99 à 1900-1999
        si (y < 100 && y >= 0) {
            args = Array.prototype.slice.call(arguments);
            // conserver les années bissextiles en utilisant un cycle complet de 400 ans, puis réinitialiser
            args[0] = y + 400 ;
            date = new Date(Date.UTC.apply(null, args));
            si (isFinite(date.getUTCFullYear())) {
                date.setUTCFullYear(y);
            }
        } autre {
            date = new Date(Date.UTC.apply(null, arguments));
        }

        date de retour;
    }

    // début-première-semaine - début-année
    fonction firstWeekOffset (année, dow, doy) {
        var // jour de la première semaine -- quel janvier est toujours dans la première semaine (4 pour iso, 1 pour autre)
            avant = 7 + dow - doy,
            // premier jour de la semaine jour de la semaine local -- quel jour de la semaine local est avant
            fwdlw = (7 + createUTCDate(year, 0, fwd).getUTCDay() - dow) % 7 ;

        return -fwdlw + fwd - 1 ;
    }

    // https://en.wikipedia.org/wiki/ISO_week_date#Calculator_a_date_given_the_year.2C_week_number_and_weekday
    fonction dayOfYearFromWeeks (année, semaine, jour de la semaine, dow, doy) {
        var localWeekday = (7 + weekday - dow) % 7,
            weekOffset = firstWeekOffset (année, dow, doy),
            dayOfYear = 1 + 7 * (week - 1) + localWeekday + weekOffset,
            resAnnée,
            resJourDeAnnée ;

        si (jourDeAnnée <= 0) {
            resAnnée = année - 1 ;
            resDayOfYear = daysInYear(resYear) + dayOfYear ;
        } sinon si (jourDeAnnée > joursDansAnnée(année)) {
            resAnnée = année + 1 ;
            resDayOfYear = dayOfYear - daysInYear(year);
        } autre {
            resAnnée = année ;
            resJourDeAnnée = jourDeAnnée ;
        }

        retour {
            année : année res,
            jourDeAnnée : resJourDeAnnée,
        } ;
    }

    function weekOfYear(maman, dow, doy) {
        var weekOffset = firstWeekOffset(mom.year(), dow, doy),
            semaine = Math.floor((mom.dayOfYear() - weekOffset - 1) / 7) + 1,
            resWeek,
            resAnnée ;

        si (semaine < 1) {
            resAnnée = maman.année() - 1;
            resWeek = semaine + weeksInYear(resYear, dow, doy);
        } else if (week > weeksInYear(mom.year(), dow, doy)) {
            resWeek = week - weeksInYear(mom.year(), dow, doy);
            resAnnée = maman.année() + 1;
        } autre {
            resAnnée = maman.année();
            resWeek = semaine ;
        }

        retour {
            semaine : resWeek,
            année : année res,
        } ;
    }

    fonction semainesDansAnnée(année, dow, doy) {
        var weekOffset = firstWeekOffset(année, dow, doy),
            weekOffsetNext = firstWeekOffset (année + 1, dow, doy);
        retour (daysInYear(year) - weekOffset + weekOffsetNext) / 7 ;
    }

    // MISE EN PAGE

    addFormatToken('w', ['ww', 2], 'wo', 'week');
    addFormatToken('W', ['WW', 2], 'Wo', 'isoWeek');

    // alias

    addUnitAlias('week', 'w');
    addUnitAlias('isoWeek', 'W');

    // PRIORITÉS

    addUnitPriority('semaine', 5);
    addUnitPriority('isoWeek', 5);

    // PARSAGE

    addRegexToken('w', match1to2);
    addRegexToken('ww', match1to2, match2);
    addRegexToken('W', match1to2);
    addRegexToken('WW', match1to2, match2);

    addWeekParseToken(
        ['w', 'ww', 'W', 'WW'],
        fonction (entrée, semaine, configuration, jeton) {
            semaine[token.substr(0, 1)] = toInt(input);
        }
    );

    // AIDE

    // LOCAUX

    function localeWeek(maman) {
        return weekOfYear(maman, this._week.dow, this._week.doy).week ;
    }

    var defaultLocaleWeek = {
        dow : 0, // le dimanche est le premier jour de la semaine.
        doy: 6, // La semaine qui contient le 6 janvier est la première semaine de l'année.
    } ;

    function localeFirstDayOfWeek() {
        retourner this._week.dow ;
    }

    function localeFirstDayOfYear() {
        retourner this._week.doy ;
    }

    // DES MOMENTS

    fonction getSetWeek (entrée) {
        var semaine = this.localeData().week(this);
        entrée de retour == null ? semaine : this.add((entrée - semaine) * 7, 'd');
    }

    fonction getSetISOWeek (entrée) {
        var semaine = semaineDeAnnée(ceci, 1, 4).semaine;
        entrée de retour == null ? semaine : this.add((entrée - semaine) * 7, 'd');
    }

    // MISE EN PAGE

    addFormatToken('j', 0, 'faire', 'jour');

    addFormatToken('dd', 0, 0, function (format) {
        retourne this.localeData().weekdaysMin(this, format);
    });

    addFormatToken('ddd', 0, 0, fonction (format) {
        renvoie this.localeData().weekdaysShort(this, format);
    });

    addFormatToken('dddd', 0, 0, fonction (format) {
        renvoie this.localeData().weekdays(this, format);
    });

    addFormatToken('e', 0, 0, 'weekday');
    addFormatToken('E', 0, 0, 'isoWeekday');

    // alias

    addUnitAlias('jour', 'j');
    addUnitAlias('weekday', 'e');
    addUnitAlias('isoWeekday', 'E');

    // PRIORITÉ
    addUnitPriority('jour', 11);
    addUnitPriority('weekday', 11);
    addUnitPriority('isoWeekday', 11);

    // PARSAGE

    addRegexToken('d', match1to2);
    addRegexToken('e', match1to2);
    addRegexToken('E', match1to2);
    addRegexToken('dd', function (isStrict, locale) {
        return locale.weekdaysMinRegex(isStrict);
    });
    addRegexToken('ddd', function (isStrict, locale) {
        return locale.weekdaysShortRegex(isStrict);
    });
    addRegexToken('dddd', function (isStrict, locale) {
        return locale.weekdaysRegex(isStrict);
    });

    addWeekParseToken(['dd', 'ddd', 'dddd'], function (input, week, config, token) {
        var weekday = config._locale.weekdaysParse(input, token, config._strict);
        // si nous n'avons pas obtenu de nom de jour de semaine, marque la date comme invalide
        si (jour de la semaine != null) {
            week.d = jour de la semaine ;
        } autre {
            getParsingFlags(config).invalidWeekday = entrée;
        }
    });

    addWeekParseToken(['d', 'e', ​​'E'], function (input, week, config, token) {
        semaine[token] = toInt(input);
    });

    // AIDE

    function parseWeekday(input, locale) {
        si (type d'entrée !== 'chaîne') {
            entrée de retour ;
        }

        si (!estNaN(entrée)) {
            return parseInt(input, 10);
        }

        input = locale.weekdaysParse(input);
        si (type d'entrée === 'nombre') {
            entrée de retour ;
        }

        renvoie nul ;
    }

    function parseIsoWeekday(input, locale) {
        si (type d'entrée === 'chaîne') {
            return locale.weekdaysParse(input) % 7 || 7;
        }
        retour estNaN(entrée) ? null : entrée ;
    }

    // LOCAUX
    fonction shiftWeekdays(ws, n) {
        return ws.slice(n, 7).concat(ws.slice(0, n));
    }

    var defaultLocaleWeekdays =
            'Dimanche_Lundi_Mardi_Mercredi_Jeudi_Vendredi_Samedi'.split('_'),
        defaultLocaleWeekdaysShort = 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        defaultLocaleWeekdaysMin = 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        defaultWeekdaysRegex = matchWord,
        defaultWeekdaysShortRegex = matchWord,
        defaultWeekdaysMinRegex = matchWord ;

    function localeJoursSemaine(m, format) {
        var jours de la semaine = estArray(this._weekdays)
            ? this._weekdays
            : this._weekdays[
                  m && m !== vrai && this._weekdays.isFormat.test(format)
                      ? 'format'
                      : 'autonome'
              ] ;
        retourner m === vrai
            ? shiftWeekdays(weekdays, this._week.dow)
            : m
            ? jours de la semaine[m.jour()]
            : jours de la semaine;
    }

    function localeWeekdaysShort(m) {
        retourner m === vrai
            ? shiftWeekdays(this._weekdaysShort, this._week.dow)
            : m
            ? this._weekdaysShort[m.day()]
            : this._weekdaysShort;
    }

    fonction localeJoursSemaineMin(m) {
        retourner m === vrai
            ? shiftWeekdays(this._weekdaysMin, this._week.dow)
            : m
            ? this._weekdaysMin[m.day()]
            : this._weekdaysMin;
    }

    function handleStrictParse$1(weekdayName, format, strict) {
        var je,
            ii,
            maman,
            llc = weekdayName.toLocaleLowerCase();
        si (!this._weekdaysParse) {
            this._weekdaysParse = [] ;
            this._shortWeekdaysParse = [] ;
            this._minWeekdaysParse = [] ;

            pour (je = 0; je < 7; ++i) {
                maman = créerUTC([2000, 1]).jour(i);
                this._minWeekdaysParse[i] = this.weekdaysMin(
                    maman,
                    ''
                ).toLocaleLowerCase();
                this._shortWeekdaysParse[i] = this.weekdaysShort(
                    maman,
                    ''
                ).toLocaleLowerCase();
                this._weekdaysParse[i] = this.weekdays(maman, '').toLocaleLowerCase();
            }
        }

        si (strict) {
            si (format === 'dddd') {
                ii = indexOf.call(this._weekdaysParse, llc);
                retour ii !== -1 ? ii : nul ;
            } sinon si (format === 'ddd') {
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                retour ii !== -1 ? ii : nul ;
            } autre {
                ii = indexOf.call(this._minWeekdaysParse, llc);
                retour ii !== -1 ? ii : nul ;
            }
        } autre {
            si (format === 'dddd') {
                ii = indexOf.call(this._weekdaysParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._minWeekdaysParse, llc);
                retour ii !== -1 ? ii : nul ;
            } sinon si (format === 'ddd') {
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._weekdaysParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._minWeekdaysParse, llc);
                retour ii !== -1 ? ii : nul ;
            } autre {
                ii = indexOf.call(this._minWeekdaysParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._weekdaysParse, llc);
                si (ii !== -1) {
                    retour ii ;
                }
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                retour ii !== -1 ? ii : nul ;
            }
        }
    }

    function localeWeekdaysParse(weekdayName, format, strict) {
        var je, maman, regex ;

        si (this._weekdaysParseExact) {
            return handleStrictParse$1.call(this, weekdayName, format, strict);
        }

        si (!this._weekdaysParse) {
            this._weekdaysParse = [] ;
            this._minWeekdaysParse = [] ;
            this._shortWeekdaysParse = [] ;
            this._fullWeekdaysParse = [] ;
        }

        pour (je = 0; je < 7; je++) {
            // crée la regex si nous ne l'avons pas déjà

            maman = créerUTC([2000, 1]).jour(i);
            if (strict && !this._fullWeekdaysParse[i]) {
                this._fullWeekdaysParse[i] = nouvelle RegExp(
                    '^' + this.weekdays(maman, '').replace('.', '\\.?') + '$',
                    'je'
                );
                this._shortWeekdaysParse[i] = nouvelle RegExp(
                    '^' + this.weekdaysShort(maman, '').replace('.', '\\.?') + '$',
                    'je'
                );
                this._minWeekdaysParse[i] = nouvelle RegExp(
                    '^' + this.weekdaysMin(maman, '').replace('.', '\\.?') + '$',
                    'je'
                );
            }
            si (!this._weekdaysParse[i]) {
                expression régulière =
                    '^' +
                    this.weekdays(maman, '') +
                    '|^' +
                    this.weekdaysShort(maman, '') +
                    '|^' +
                    this.weekdaysMin(maman, '');
                this._weekdaysParse[i] = new RegExp(regex.replace('.', ''), 'i');
            }
            // teste la regex
            si (
                strict &&
                format === 'jjjj' &&
                this._fullWeekdaysParse[i].test(weekdayName)
            ) {
                retourner je ;
            } sinon si (
                strict &&
                format === 'jjj' &&
                this._shortWeekdaysParse[i].test(weekdayName)
            ) {
                retourner je ;
            } sinon si (
                strict &&
                format === 'jj' &&
                this._minWeekdaysParse[i].test(weekdayName)
            ) {
                retourner je ;
            } sinon si (!strict && this._weekdaysParse[i].test(weekdayName)) {
                retourner je ;
            }
        }
    }

    // DES MOMENTS

    fonction getSetDayOfWeek (entrée) {
        si (!this.isValid()) {
            entrée de retour != null ? ceci : NaN;
        }
        var jour = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
        si (entrée != null) {
            input = parseWeekday(input, this.localeData());
            return this.add(input - day, 'd');
        } autre {
            jour de retour ;
        }
    }

    fonction getSetLocaleDayOfWeek (entrée) {
        si (!this.isValid()) {
            entrée de retour != null ? ceci : NaN;
        }
        var weekday = (this.day() + 7 - this.localeData()._week.dow) % 7;
        entrée de retour == null ? jour de la semaine : this.add(input - jour de la semaine, 'd');
    }

    fonction getSetISODayOfWeek (entrée) {
        si (!this.isValid()) {
            entrée de retour != null ? ceci : NaN;
        }

        // se comporte comme moment#jour sauf
        // en tant que getter, renvoie 7 au lieu de 0 (plage 1-7 au lieu de 0-6)
        // en tant que passeur, le dimanche doit appartenir à la semaine précédente.

        si (entrée != null) {
            var weekday = parseIsoWeekday(input, this.localeData());
            return this.day(this.day() % 7 ? weekday : weekday - 7);
        } autre {
            return this.day() || 7;
        }
    }

    function weekdaysRegex(isStrict) {
        si (this._weekdaysParseExact) {
            si (!hasOwnProp(this, '_weekdaysRegex')) {
                computeWeekdaysParse.call(this);
            }
            si (estStrict) {
                renvoie ceci._weekdaysStrictRegex ;
            } autre {
                retourne this._weekdaysRegex ;
            }
        } autre {
            si (!hasOwnProp(this, '_weekdaysRegex')) {
                this._weekdaysRegex = defaultWeekdaysRegex ;
            }
            retourner ceci._weekdaysStrictRegex && isStrict
                ? this._weekdaysStrictRegex
                : this._weekdaysRegex;
        }
    }

    function weekdaysShortRegex(isStrict) {
        si (this._weekdaysParseExact) {
            si (!hasOwnProp(this, '_weekdaysRegex')) {
                computeWeekdaysParse.call(this);
            }
            si (estStrict) {
                renvoie ceci._weekdaysShortStrictRegex ;
            } autre {
                renvoie ceci._weekdaysShortRegex ;
            }
        } autre {
            si (!hasOwnProp(this, '_weekdaysShortRegex')) {
                this._weekdaysShortRegex = defaultWeekdaysShortRegex ;
            }
            renvoie ceci._weekdaysShortStrictRegex && isStrict
                ? this._weekdaysShortStrictRegex
                : this._weekdaysShortRegex ;
        }
    }

    function weekdaysMinRegex(isStrict) {
        si (this._weekdaysParseExact) {
            si (!hasOwnProp(this, '_weekdaysRegex')) {
                computeWeekdaysParse.call(this);
            }
            si (estStrict) {
                renvoie ceci._weekdaysMinStrictRegex ;
            } autre {
                retourne this._weekdaysMinRegex ;
            }
        } autre {
            si (!hasOwnProp(this, '_weekdaysMinRegex')) {
                this._weekdaysMinRegex = defaultWeekdaysMinRegex ;
            }
            retourner ceci._weekdaysMinStrictRegex && isStrict
                ? this._weekdaysMinStrictRegex
                : this._weekdaysMinRegex;
        }
    }

    function calculateWeekdaysParse() {
        fonction cmpLenRev(a, b) {
            return b.length - a.length ;
        }

        var minPieces = [],
            pièces courtes = [],
            longPieces = [],
            pièces mixtes = [],
            je,
            maman,
            minp,
            bref,
            longp ;
        pour (je = 0; je < 7; je++) {
            // crée la regex si nous ne l'avons pas déjà
            maman = créerUTC([2000, 1]).jour(i);
            minp = regexEscape(this.weekdaysMin(maman, ''));
            shortp = regexEscape(this.weekdaysShort(maman, ''));
            longp = regexEscape(this.weekdays(maman, ''));
            minPieces.push(minp);
            shortPieces.push(shortp);
            longPieces.push(longp);
            mixedPieces.push(minp);
            mixedPieces.push(shortp);
            mixedPieces.push(longp);
        }
        // Le tri vérifie si un jour de la semaine (ou abbr) est un préfixe d'un autre
        // correspondra à la pièce la plus longue.
        minPieces.sort(cmpLenRev);
        shortPieces.sort(cmpLenRev);
        longPieces.sort(cmpLenRev);
        mixedPieces.sort(cmpLenRev);

        this._weekdaysRegex = new RegExp('^(' + mixedPieces.join('|') + ')', 'i');
        this._weekdaysShortRegex = this._weekdaysRegex ;
        this._weekdaysMinRegex = this._weekdaysRegex ;

        this._weekdaysStrictRegex = nouvelle RegExp(
            '^(' + longPieces.join('|') + ')',
            'je'
        );
        this._weekdaysShortStrictRegex = nouvelle RegExp(
            '^(' + shortPieces.join('|') + ')',
            'je'
        );
        this._weekdaysMinStrictRegex = new RegExp(
            '^(' + minPieces.join('|') + ')',
            'je'
        );
    }

    // MISE EN PAGE

    fonction hFormat() {
        retourne this.hours() % 12 || 12;
    }

    fonction kFormat() {
        retourne this.hours() || 24;
    }

    addFormatToken('H', ['HH', 2], 0, 'heure');
    addFormatToken('h', ['hh', 2], 0, hFormat);
    addFormatToken('k', ['kk', 2], 0, kFormat);

    addFormatToken('hmm', 0, 0, fonction () {
        return '' + hFormat.apply(this) + zeroFill(this.minutes(), 2);
    });

    addFormatToken('hmmss', 0, 0, fonction () {
        retour (
            '' +
            hFormat.apply(this) +
            zéroRemplir(this.minutes(), 2) +
            zeroFill(this.seconds(), 2)
        );
    });

    addFormatToken('Hmm', 0, 0, fonction () {
        return '' + this.hours() + zeroFill(this.minutes(), 2);
    });

    addFormatToken('Hmmss', 0, 0, fonction () {
        retour (
            '' +
            cette.heures() +
            zéroRemplir(this.minutes(), 2) +
            zeroFill(this.seconds(), 2)
        );
    });

    fonction meridiem(token, minuscule) {
        addFormatToken(jeton, 0, 0, fonction () {
            retourne this.localeData().meridiem(
                this.hours(),
                this.minutes(),
                minuscule
            );
        });
    }

    meridiem('a', vrai);
    meridiem('A', faux);

    // alias

    addUnitAlias('heure', 'h');

    // PRIORITÉ
    addUnitPriority('heure', 13);

    // PARSAGE

    function matchMeridiem(isStrict, locale) {
        renvoie locale._meridiemParse ;
    }

    addRegexToken('a', matchMeridiem);
    addRegexToken('A', matchMeridiem);
    addRegexToken('H', match1to2);
    addRegexToken('h', match1to2);
    addRegexToken('k', match1to2);
    addRegexToken('HH', match1to2, match2);
    addRegexToken('hh', match1to2, match2);
    addRegexToken('kk', match1to2, match2);

    addRegexToken('hmm', match3to4);
    addRegexToken('hmmss', match5to6);
    addRegexToken('Hmm', match3to4);
    addRegexToken('Hmmss', match5to6);

    addParseToken(['H', 'HH'], HEURE);
    addParseToken(['k', 'kk'], fonction (entrée, tableau, config) {
        var kInput = toInt(input);
        tableau[HEURE] = kEntrée === 24 ? 0 : kEntrée ;
    });
    addParseToken(['a', 'A'], fonction (entrée, tableau, config) {
        config._isPm = config._locale.isPM(entrée);
        config._meridiem = entrée ;
    });
    addParseToken(['h', 'hh'], fonction (entrée, tableau, config) {
        tableau[HEURE] = toInt(input);
        getParsingFlags(config).bigHour = true ;
    });
    addParseToken('hmm', fonction (entrée, tableau, config) {
        var pos = input.length - 2;
        array[HEURE] = toInt(input.substr(0, pos));
        array[MINUTE] = toInt(input.substr(pos));
        getParsingFlags(config).bigHour = true ;
    });
    addParseToken('hmmss', fonction (entrée, tableau, configuration) {
        var pos1 = entrée.longueur - 4,
            pos2 = input.length - 2 ;
        array[HEURE] = toInt(input.substr(0, pos1));
        array[MINUTE] = toInt(input.substr(pos1, 2));
        array[SECOND] = toInt(input.substr(pos2));
        getParsingFlags(config).bigHour = true ;
    });
    addParseToken('Hmm', fonction (entrée, tableau, config) {
        var pos = input.length - 2;
        array[HEURE] = toInt(input.substr(0, pos));
        array[MINUTE] = toInt(input.substr(pos));
    });
    addParseToken('Hmmss', fonction (entrée, tableau, configuration) {
        var pos1 = entrée.longueur - 4,
            pos2 = input.length - 2 ;
        array[HEURE] = toInt(input.substr(0, pos1));
        array[MINUTE] = toInt(input.substr(pos1, 2));
        array[SECOND] = toInt(input.substr(pos2));
    });

    // LOCAUX

    function localeIsPM(input) {
        // Le mode Quirks IE8 et le mode Standards IE7 n'autorisent pas l'accès aux chaînes comme les tableaux
        // L'utilisation de charAt devrait être plus compatible.
        return (input + '').toLowerCase().charAt(0) === 'p';
    }

    var defaultLocaleMeridiemParse = /[ap]\.?m?\.?/i,
        // La définition de l'heure doit conserver l'heure, car l'utilisateur explicitement
        // spécifié quelle heure ils veulent. Donc, en essayant de maintenir la même heure (en
        // un nouveau fuseau horaire) a du sens. L'ajout/soustraction d'heures ne suit pas
        // cette règle.
        getSetHour = makeGetSet('Heures', true);

    function localeMeridiem(hours, minutes, isLower) {
        si (heures > 11) {
            retour est inférieur ? 'pm' : 'PM';
        } autre {
            retour est inférieur ? 'am' : 'AM';
        }
    }

    var configuration de base = {
        calendrier : defaultCalendar,
        longDateFormat : par défautLongDateFormat,
        invalidDate : defaultInvalidDate,
        ordinal : defaultOrdinal,
        dayOfMonthOrdinalParse : defaultDayOfMonthOrdinalParse,
        heurerelative : heurerelative par défaut,

        mois : defaultLocaleMonths,
        moisCourt : par défautLocaleMonthsCourt,

        semaine : defaultLocaleWeek,

        jours de la semaine : defaultLocaleWeekdays,
        weekdaysMin : defaultLocaleWeekdaysMin,
        weekdaysShort : defaultLocaleWeekdaysShort,

        meridiemParse : defaultLocaleMeridiemParse,
    } ;

    // stockage interne pour les fichiers de configuration locale
    var locales = {},
        localesFamilies = {},
        globalLocal ;

    fonction commonPrefix(arr1, arr2) {
        var je,
            minl = Math.min(arr1.length, arr2.length);
        pour (je = 0; je < minl; je += 1) {
            si (tab1[i] !== tab2[i]) {
                retourner je ;
            }
        }
        retour minl ;
    }

    function normaliseLocale(clé) {
        touche retour ? key.toLowerCase().replace('_', '-') : clé;
    }

    // sélectionne la locale dans le tableau
    // essayez ['en-au', 'en-gb'] comme 'en-au', 'en-gb', 'en', comme dans parcourir la liste en essayant chacun
    // sous-chaîne du plus spécifique au moins, mais passe à l'élément de tableau suivant s'il s'agit d'une variante plus spécifique que la racine actuelle
    fonction choisirLocal(noms) {
        var je = 0,
            j,
            suivant,
            lieu,
            diviser;

        tandis que (je < noms.longueur) {
            split = normalizeLocale(names[i]).split('-');
            j = split.longueur ;
            suivant = normalizeLocale(names[i + 1]);
            suivant = suivant ? suivant.split('-') : null;
            tandis que (j > 0) {
                locale = loadLocale(split.slice(0, j).join('-'));
                si (paramètres régionaux) {
                    renvoyer les paramètres régionaux ;
                }
                si (
                    suivant &&
                    longueur.suivante >= j &&
                    commonPrefix(split, next) >= j - 1
                ) {
                    // le prochain élément du tableau est meilleur qu'une sous-chaîne moins profonde de celui-ci
                    casser;
                }
                j-- ;
            }
            je++ ;
        }
        retourner globalLocal ;
    }

    function isLocaleNameSane(nom) {
        // Empêche les noms qui ressemblent à des chemins de système de fichiers, c'est-à-dire contiennent '/' ou '\'
        return name.match('^[^/\\\\]*$') != null;
    }

    fonction loadLocale(nom) {
        var oldLocale = null,
            aliasedRequire ;
        // TODO : trouver une meilleure façon d'enregistrer et de charger toutes les locales dans Node
        si (
            locales[nom] === non défini &&
            typeof module !== 'indéfini' &&
            modules &&
            module.exports &&
            isLocaleNameSane(nom)
        ) {
            essayer {
                oldLocale = globalLocale._abbr ;
                aliasedRequire = requiert ;
                aliasedRequire('./locale/' + nom);
                getSetGlobalLocale(oldLocale);
            } attrape (e) {
                // marquer comme introuvable pour éviter la répétition d'un fichier coûteux nécessitant un appel provoquant un processeur élevé
                // lorsque vous essayez de trouver en-US, en_US, en-us pour chaque appel de format
                locales[nom] = null ; // null signifie introuvable
            }
        }
        renvoie les paramètres régionaux[nom] ;
    }

    // Cette fonction chargera les paramètres régionaux puis définira les paramètres régionaux globaux. Si
    // aucun argument n'est passé, il renverra simplement le global actuel
    // clé locale.
    fonction getSetGlobalLocale(clé, valeurs) {
        données var ;
        si (clé) {
            if (isUndefined(values)) {
                données = getLocale(clé);
            } autre {
                data = defineLocale(clé, valeurs);
            }

            si (données) {
                // moment.duration._locale = moment._locale = data ;
                globalLocal = données ;
            } autre {
                if (typeof console !== 'undefined' && console.warn) {
                    // avertir l'utilisateur si des arguments sont passés mais que les paramètres régionaux n'ont pas pu être définis
                    console.warn(
                        'Paramètres régionaux ' + clé + ' introuvables. Avez-vous oublié de le charger?'
                    );
                }
            }
        }

        return globalLocale._abbr ;
    }

    function définirLocale(nom, config) {
        si (config !== null) {
            var locale,
                parentConfig = baseConfig ;
            config.abbr = nom ;
            if (locales[nom] != null) {
                obsolèteSimple(
                    'defineLocaleOverride',
                    'utilisez moment.updateLocale(localeName, config) pour changer ' +
                        'un lieu existant. moment.defineLocale(localeName, ' +
                        'config) ne doit être utilisé que pour créer une nouvelle locale ' +
                        'Voir http://momentjs.com/guides/#/warnings/define-locale/ pour plus d'informations.'
                );
                parentConfig = locales[nom]._config ;
            } sinon si (config.parentLocale != null) {
                if (locales[config.parentLocal e] != null) {
                    parentConfig = locales[config.parentLocale]._config ;
                } autre {
                    locale = loadLocale(config.parentLocale);
                    if (locale != null) {
                        parentConfig = paramètres régionaux._config ;
                    } autre {
                        si (!localeFamilies[config.parentLocale]) {
                            localeFamilies[config.parentLocale] = [] ;
                        }
                        localeFamilies[config.parentLocale].push({
                            nom nom,
                            configuration : configuration,
                        });
                        renvoie nul ;
                    }
                }
            }
            locales[nom] = new Locale(mergeConfigs(parentConfig, config));

            if (localeFamilies[nom]) {
                localeFamilies[nom].forEach(fonction (x) {
                    defineLocale(x.nom, x.config);
                });
            }

            // rétrocompatibilité pour l'instant : définissez également les paramètres régionaux
            // assurez-vous que nous définissons les paramètres régionaux APRÈS que tous les paramètres régionaux enfants aient été
            // créé, donc nous ne nous retrouverons pas avec le jeu de paramètres régionaux enfant.
            getSetGlobalLocale(nom);

            renvoie les paramètres régionaux[nom] ;
        } autre {
            // utile pour tester
            supprimer les paramètres régionaux[nom] ;
            renvoie nul ;
        }
    }

    fonction updateLocale(nom, config) {
        si (config != null) {
            var locale,
                tmpLocale,
                parentConfig = baseConfig ;

            if (locales[nom] != null && locales[nom].parentLocale != null) {
                // Mettre à jour les paramètres régionaux enfants existants sur place pour éviter les fuites de mémoire
                locales[nom].set(mergeConfigs(locales[nom]._config, config));
            } autre {
                // FUSIONNER
                tmpLocale = loadLocale(nom);
                si (tmpLocale != null) {
                    parentConfig = tmpLocale._config ;
                }
                config = mergeConfigs(parentConfig, config);
                si (tmpLocale == null) {
                    // updateLocale est appelé pour créer une nouvelle locale
                    // Définissez abbr pour qu'il ait un nom (les getters renvoient
                    // non défini sinon).
                    config.abbr = nom ;
                }
                locale = new Locale(config);
                locale.parentLocale = locales[nom] ;
                locales[nom] = locale ;
            }

            // rétrocompatibilité pour l'instant : définissez également les paramètres régionaux
            getSetGlobalLocale(nom);
        } autre {
            // passe null pour la configuration à annuler la mise à jour, utile pour les tests
            if (locales[nom] != null) {
                if (locales[nom].parentLocale != null) {
                    locales[nom] = locales[nom].parentLocale ;
                    si (nom === getSetGlobalLocale()) {
                        getSetGlobalLocale(nom);
                    }
                } else if (locales[nom] != null) {
                    supprimer les paramètres régionaux[nom] ;
                }
            }
        }
        renvoie les paramètres régionaux[nom] ;
    }

    // renvoie les données locales
    fonction getLocale(clé) {
        variable locale ;

        si (clé && clé._locale && clé._locale._abbr) {
            clé = clé._locale._abbr ;
        }

        si (!clé) {
            retourner globalLocal ;
        }

        si (!isArray(clé)) {
            //court-circuite tout le reste
            locale = loadLocale(clé);
            si (paramètres régionaux) {
                renvoyer les paramètres régionaux ;
            }
            clé = [clé] ;
        }

        return chooseLocale(key);
    }

    fonction listLocales() {
        clés de retour (locales);
    }

    fonction checkOverflow(m) {
        débordement var,
            a = m._a ;

        if (a && getParsingFlags(m).overflow === -2) {
            débordement =
                un[MOIS] < 0 || a[MOIS] > 11
                    ? MOIS
                    : a[DATE] < 1 || a[DATE] > joursDansMois(a[ANNEE], a[MOIS])
                    ? DATE
                    : a[HEURE] < 0 ||
                      a[HEURE] > 24 ||
                      (a[HEURE] === 24 &&
                          (a[MINUTE] !== 0 ||
                              a[DEUXIEME] !== 0 ||
                              a[MILLISECONDE] !== 0))
                    ? HEURE
                    : a[MINUTE] < 0 || a[MINUTE] > 59
                    ? MINUTE
                    : a[SECONDE] < 0 || a[SECONDE] > 59
                    ? DEUXIÈME
                    : a[MILLISECONDE] < 0 || a[MILLISECONDE] > 999
                    ? MILLISECONDE
                    : -1;

            si (
                getParsingFlags(m)._overflowDayOfYear &&
                (débordement < ANNÉE || débordement > DATE)
            ) {
                débordement = DATE ;
            }
            si (getParsingFlags(m)._overflowWeeks && débordement === -1) {
                débordement = SEMAINE ;
            }
            if (getParsingFlags(m)._overflowWeekday && overflow === -1) {
                débordement = JOUR DE SEMAINE ;
            }

            getParsingFlags(m).overflow = débordement ;
        }

        retourner m ;
    }

    // expression régulière iso 8601
    // 0000-00-00 0000-W00 ou 0000-W00-0 + T + 00 ou 00:00 ou 00:00:00 ou 00:00:00.000 + +00:00 ou +0000 ou +00)
    var extendedIsoRegex =
            /^\s*((?:[+-]\d{6}|\d{4})-(?:\d\d-\d\d|W\d\d-\d|W\ d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?::\d\d(?::\d\d(?:[. ,]\d+)?)?)?)([+-]\d\d(?::?\d\d)?|\s*Z)?)?$/,
        basicIsoRegex =
            /^\s*((?:[+-]\d{6}|\d{4})(?:\d\d\d\d|W\d\d\d|W\d\d |\d\d\d|\d\d|))(?:(T| )(\d\d(?:\d\d(?:\d\d(?:[.,]\d+ )?)?)?)([+-]\d\d(?::?\d\d)?|\s*Z)?)?$/,
        tzRegex = /Z|[+-]\d\d(?::?\d\d)?/,
        isoDates = [
            ['AAAAAA-MM-JJ', /[+-]\d{6}-\d\d-\d\d/],
            ['AAAA-MM-JJ', /\d{4}-\d\d-\d\d/],
            ['GGGG-[W]WW-E', /\d{4}-W\d\d-\d/],
            ['GGGG-[W]WW', /\d{4}-W\d\d/, faux],
            ['AAAA-JJJ', /\d{4}-\d{3}/],
            ['AAAA-MM', /\d{4}-\d\d/, faux],
            ['AAAAAAMMJJ', /[+-]\d{10}/],
            ['AAAAMMJJ', /\d{8}/],
            ['GGGG[W]WWE', /\d{4}W\d{3}/],
            ['GGGG[W]WW', /\d{4}W\d{2}/, faux],
            ['AAAAJJJ', /\d{7}/],
            ['AAAAMM', /\d{6}/, faux],
            ['AAAA', /\d{4}/, faux],
        ],
        // formats d'heure iso et regexes
        isoTimes = [
            ['HH:mm:ss.SSSS', /\d\d:\d\d:\d\d\.\d+/],
            ['HH:mm:ss,SSSS', /\d\d:\d\d:\d\d,\d+/],
            ['HH:mm:ss', /\d\d:\d\d:\d\d/],
            ['HH:mm', /\d\d:\d\d/],
            ['HHmmss.SSSS', /\d\d\d\d\d\d\.\d+/],
            ['HHmmss,SSSS', /\d\d\d\d\d\d,\d+/],
            ['HHmmss', /\d\d\d\d\d\d/],
            ['HHmm', /\d\d\d\d/],
            ['HH', /\d\d/],
        ],
        aspNetJsonRegex = /^\/?Date\((-?\d+)/i,
        // RFC 2822 regex : pour plus de détails, voir https://tools.ietf.org/html/rfc2822#section-3.3
        rfc2822 =
            /^(?:(Lun|Mar|Mer|Jeu|Ven|Sam|Dim),?\s)?(\d{1,2})\s(Jan|Fév|Mar|Avr|Mai|Juin| Juil|Août|Sep|Oct|Nov|Déc)\s(\d{2,4})\s(\d\d):(\d\d)(?::(\d\d)) ? \s(?:(UT|GMT|[ECMP][SD]T)|([Zz])|([+-]\d{4}))$/,
        obsOffsets = {
            TU : 0,
            GMT : 0,
            HAE : -4 * 60,
            HNE : -5 * 60,
            CDT : -5 * 60,
            TSC : -6 * 60,
            MDT : -6 * 60,
            MST : -7 * 60,
            HAP : -7 * 60,
            PST : -8 * 60,
        } ;

    // date du format iso
    fonction configDepuisISO(config) {
        var je,
            je,
            chaîne = config._i,
            match = extendedIsoRegex.exec(chaîne) || basicIsoRegex.exec(chaîne),
            laisser du temps,
            format de date,
            format de l'heure,
            tzFormat,
            isoDatesLen = isoDates.longueur,
            isoTimesLen = isoTimes.length ;

        si (concordance) {
            getParsingFlags(config).iso = true;
            pour (je = 0, l = isoDatesLen; je < l; je++) {
                si (isoDates[i][1].exec(match[1])) {
                    dateFormat = isoDates[i][0] ;
                    allowTime = isoDates[i][2] !== false ;
                    casser;
                }
            }
            si (dateFormat == null) {
                config._isValid = faux ;
                retour;
            }
            si (match[3]) {
                pour (i = 0, l = isoTimesLen; je < l; i++) {
                    si (isoTimes[i][1].exec(match[3])) {
                        // match[2] doit être 'T' ou espace
                        timeFormat = (match[2] || ' ') + isoTimes[i][0] ;
                        casser;
                    }
                }
                si (timeFormat == null) {
                    config._isValid = faux ;
                    retour;
                }
            }
            if (!allowTime && timeFormat != null) {
                config._isValid = faux ;
                retour;
            }
            si (match[4]) {
                si (tzRegex.exec(match[4])) {
                    tzFormat = 'Z';
                } autre {
                    config._isValid = faux ;
                    retour;
                }
            }
            config._f = dateFormat + (timeFormat || '') + (tzFormat || '');
            configFromStringAndFormat(config);
        } autre {
            config._isValid = faux ;
        }
    }

    fonction extractFromRFC2822Strings(
        annéeStr,
        moisStr,
        jourStr,
        heureStr,
        minuteStr,
        secondeStr
    ) {
        var résultat = [
            untruncateYear(yearStr),
            defaultLocaleMonthsShort.indexOf(monthStr),
            parseInt(dayStr, 10),
            parseInt(hourStr, 10),
            parseInt(minuteStr, 10),
        ] ;

        si (secondStr) {
            result.push(parseInt(secondStr, 10));
        }

        retourner le résultat ;
    }

    function untruncateYear(yearStr) {
        var année = parseInt(annéeStr, 10);
        si (année <= 49) {
            retour 2000 + an;
        } sinon si (année <= 999) {
            retour 1900 + année;
        }
        année de retour ;
    }

    fonction preprocessRFC2822(s) {
        // Supprime les commentaires et les espaces vides et remplace les espaces multiples par un seul espace
        Retour
            .replace(/\([^()]*\)|[\n\t]/g, ' ')
            .replace(/(\s\s+)/g, ' ')
            .replace(/^\s\s*/, '')
            .replace(/\s\s*$/, '');
    }

    function checkWeekday(weekdayStr, parsedInput, config) {
        si (weekdayStr) {
            // TODO : remplacer l'objet vanilla JS Date par une vérification indépendante du jour de la semaine.
            var weekdayProvided = defaultLocaleWeekdaysShort.indexOf(weekdayStr),
                weekdayActual = nouvelle Date(
                    parsedInput[0],
                    parsedInput[1],
                    parsedInput[2]
                ).getJour();
            if (weekdayProvided !== weekdayActual) {
                getParsingFlags(config).weekdayMismatch = true ;
                config._isValid = faux ;
                retourner faux ;
            }
        }
        retourner vrai ;
    }

    function calculateOffset(obsOffset, militaryOffset, numOffset) {
        si (obsOffset) {
            return obsOffsets[obsOffset] ;
        } sinon si (militaryOffset) {
            // le seul tz militaire autorisé est Z
            renvoie 0 ;
        } autre {
            var hm = parseInt(numOffset, 10),
                m = hm % 100,
                h = (hm - m) / 100 ;
            retour h * 60 + m;
        }
    }

    // date et heure au format ref 2822
    fonction configFromRFC2822(config) {
        var match = rfc2822.exec(preprocessRFC2822(config._i)),
            parsedArray ;
        si (concordance) {
            parsedArray = extractFromRFC2822Strings(
                correspondance[4],
                match[3],
                correspondance[2],
                match[5],
                match[6],
                match[7]
            );
            if (!checkWeekday(match[1], parsedArray, config)) {
                retour;
            }

            config._a = parsedArray ;
            config._tzm = calculateOffset(match[8], match[9], match[10]);

            config._d = createUTCDate.apply(null, config._a);
            config._d.setUTCMinutes(config._d.getUTCMinutes() - config._tzm);

            getParsingFlags(config).rfc2822 = vrai ;
        } autre {
            config._isValid = faux ;
        }
    }

    // date de 1) ASP.NET, 2) ISO, 3) formats RFC 2822, ou 4) secours facultatif si l'analyse n'est pas stricte
    fonction configFromString(config) {
        var correspondant = aspNetJsonRegex.exec(config._i);
        si (correspond à !== null) {
            config._d = nouvelle Date(+matched[1]);
            retour;
        }

        configFromISO(config);
        si (config._isValid === faux) {
            supprimer config._isValid ;
        } autre {
            retour;
        }

        configFromRFC2822(config);
        si (config._isValid === faux) {
            supprimer config._isValid ;
        } autre {
            retour;
        }

        si (config._strict) {
            config._isValid = faux ;
        } autre {
            // Dernière tentative, utilisez Input Fallback
            hooks.createFromInputFallback(config);
        }
    }

    hooks.createFromInputFallback = obsolète(
        'la valeur fournie n'est pas dans un format RFC2822 ou ISO reconnu. la construction du moment revient à js Date(), ' +
            'qui n'est pas fiable sur tous les navigateurs et toutes les versions. Les formats de date non RFC2822/ISO sont ' +
            'découragé. Veuillez vous référer à http://momentjs.com/guides/#/warnings/js-date/ pour plus d'informations.',
        fonction (config) {
            config._d = new Date(config._i + (config._useUTC ? ' UTC' : ''));
        }
    );

    // Choisissez le premier argument défini de deux ou trois arguments.
    fonction par défaut (a, b, c) {
        si (un != nul) {
            retourner un ;
        }
        si (b != nul) {
            retour b;
        }
        retour c;
    }

    fonction currentDateArray(config) {
        // hooks est en fait l'objet moment exporté
        var nowValue = new Date(hooks.now());
        si (config._useUTC) {
            retour [
                nowValue.getUTCFullYear(),
                nowValue.getUTCMois(),
                nowValue.getUTCDate(),
            ] ;
        }
        return [nowValue.getFullYear(), nowValue.getMonth(), nowValue.getDate()] ;
    }

    // convertit un tableau en une date.
    // le tableau doit refléter les paramètres ci-dessous
    // remarque : toutes les valeurs après l'année sont facultatives et prendront par défaut la valeur la plus basse possible.
    // [année, mois, jour , heure, minute, seconde, milliseconde]
    fonction configFromArray(config) {
        var je,
            date,
            entrée = [],
            date actuelle,
            attendusemaine,
            annéeAUtiliser ;

        si (config._d) {
            retour;
        }

        currentDate = currentDateArray(config);

        // calcule le jour de l'année à partir des semaines et des jours de la semaine
        si (config._w && config._a[DATE] == null && config._a[MONTH] == null) {
            dayOfYearFromWeekInfo(config);
        }

        //si le jour de l'année est défini, déterminez ce qu'il est
        si (config._dayOfYear != null) {
            yearToUse = defaults(config._a[YEAR], currentDate[YEAR]);

            si (
                config._dayOfYear > daysInYear(yearToUse) ||
                config._dayOfYear === 0
            ) {
                getParsingFlags(config)._overflowDayOfYear = true ;
            }

            date = createUTCDate(yearToUse, 0, config._dayOfYear);
            config._a[MOIS] = date.getUTCMois();
            config._a[DATE] = date.getUTCDate();
        }

        // Par défaut à la date actuelle.
        // * si aucune année, mois, jour du mois n'est donné, aujourd'hui par défaut
        // * si le jour du mois est donné, mois et année par défaut
        // * si le mois est donné, seule l'année par défaut
        // * si l'année est donnée, ne rien mettre par défaut
        for (i = 0; i < 3 && config._a[i] == null; ++i) {
            config._a[i] = input[i] = currentDate[i] ;
        }

        // Zéro tout ce qui n'était pas par défaut, y compris l'heure
        pour (; je < 7; je++) {
            config._a[i] = entrée[i] =
                config._a[i] == null ? (i === 2 ? 1 : 0) : config._a[i];
        }

        // Vérifier 24:00:00.000
        si (
            config._a[HEURE] === 24 &&
            config._a[MINUTE] === 0 &&
            config._a[SECOND] === 0 &&
            config._a[MILLISECOND] === 0
        ) {
            config._nextDay = vrai ;
            config._a[HEURE] = 0 ;
        }

        config._d = (config._useUTC ? createUTCDate : createDate).apply(
            nul,
            saisir
        );
        expectWeekday = config._useUTC
            ? config._d.getUTCDay()
            : config._d.getDay();

        // Appliquez le décalage de fuseau horaire à partir de l'entrée. L'utcOffset réel peut être modifié
        // avec parseZone.
        si (config._tzm != null) {
            config._d.setUTCMinutes(config._d.getUTCMinutes() - config._tzm);
        }

        si (config._nextDay) {
            config._a[HEURE] = 24 ;
        }

        // vérifie les jours de la semaine qui ne correspondent pas
        si (
            config._w &&
            typeof config._w.d !== 'non défini' &&
            config._w.d !== attenduJour de la semaine
        ) {
            getParsingFlags(config).weekdayMismatch = true ;
        }
    }

    function dayOfYearFromWeekInfo(config) {
        var w, weekYear, week, weekday, dow, doy, temp, weekdayOverflow, curWeek ;

        w = config._w ;
        if (w.GG != null || wW != null || wE != null) {
            bas = 1 ;
            doy = 4 ;

            // TODO : Nous devons prendre l'isoWeekYear actuel, mais cela dépend de
            // comment nous interprétons maintenant (local, utc, décalage fixe). Alors créez
            // une version actuelle de la configuration actuelle (prenez les drapeaux local/utc/offset, et
            // créer maintenant).
            semaineAnnée = défauts(
                avec GG,
                config._a[ANNEE],
                weekOfYear(createLocal(), 1, 4).année
            );
            semaine = défauts(wW, 1);
            jour de la semaine = défauts(wE, 1);
            si (jour de la semaine < 1 || jour de la semaine > 7) {
                WeekdayOverflow = true ;
            }
        } autre {
            dow = config._locale._week.dow ;
            doy = config._locale._week.doy ;

            curWeek = weekOfYear(createLocal(), dow, doy);

            weekYear = defaults(w.gg, config._a[YEAR], curWeek.year);

            // Par défaut la semaine en cours.
            week = defaults(ww, curWeek.week);

            si (wd != null) {
                // jour de la semaine -- les nombres de jours faibles sont pris en compte la semaine prochaine
                jour de la semaine = wd ;
                si (jour de la semaine < 0 || jour de la semaine > 6) {
                    WeekdayOverflow = true ;
                }
            } sinon si (nous != null) {
                // jour de semaine local -- le comptage commence à partir du début de la semaine
                jour de la semaine = we + dow ;
                si (nous < 0 || nous > 6) {
                    WeekdayOverflow = true ;
                }
            } autre {
                // par défaut au début de la semaine
                jour de la semaine = dow ;
            }
        }
        if (week < 1 || week > weeksInYear(weekYear, dow, doy)) {
            getParsingFlags(config)._overflowWeeks = true ;
        } sinon si (weekdayOverflow != null) {
            getParsingFlags(config)._overflowWeekday = true ;
        } autre {
            temp = dayOfYearFromWeeks(weekYear, week, weekday, dow, doy);
            config._a[ANNÉE] = temp.année ;
            config._dayOfYear = temp.dayOfYear ;
        }
    }

    // constante qui fait référence à la norme ISO
    crochets.ISO_8601 = fonction () {} ;

    // constante qui fait référence au formulaire RFC 2822
    crochets.RFC_2822 = fonction () {} ;

    // date de chaîne et chaîne de format
    fonction configFromStringAndFormat(config) {
        // TODO : déplacez ceci vers une autre partie du flux de création pour éviter les profondeurs circulaires
        si (config._f === hooks.ISO_8601) {
            configFromISO(config);
            retour;
        }
        si (config._f === hooks.RFC_2822) {
            configFromRFC2822(config);
            retour;
        }
        config._a = [] ;
        getParsingFlags(config).empty = vrai ;

        // Ce tableau est utilisé pour créer une Date, soit avec `new Date` ou `Date.UTC`
        var chaîne = '' + config._i,
            je,
            entrée analysée,
            jetons,
            jeton,
            sauté,
            stringLength = chaîne.longueur,
            totalParsedInputLength = 0,
            ère,
            tokenLen ;

        jetons =
            expandFormat(config._f, config._locale).match(formattingTokens) || [] ;
        tokenLen = jetons.longueur ;
        pour (je = 0; je < tokenLen; je++) {
            jeton = jetons[i] ;
            parsedInput = (string.match(getParseRegexForToken(token, config)) ||
                [])[0] ;
            si (entrée analysée) {
                ignoré = string.substr(0, string.indexOf(parsedInput));
                si (ignoré.longueur > 0) {
                    getParsingFlags(config).unusedInput.push(ignoré);
                }
                chaîne = chaîne.tranche(
                    string.indexOf(parsedInput) + parsedInput.length
                );
                totalParsedInputLength += parsedInput.length ;
            }
            // ne pas analyser si ce n'est pas un jeton connu
            si (formatTokenFunctions[jeton]) {
                si (entrée analysée) {
                    getParsingFlags(config).empty = faux ;
                } autre {
                    getParsingFlags(config).unusedTokens.push(token);
                }
                addTimeToArrayFromToken(token, parsedInput, config);
            } sinon si (config._strict && !parsedInput) {
                getParsingFlags(config).unusedTokens.push(token);
            }
        }

        // ajoute la longueur d'entrée non analysée restante à la chaîne
        getParsingFlags(config).charsLeftOver =
            stringLength - totalParsedInputLength ;
        si (chaîne.longueur > 0) {
            getParsingFlags(config).unusedInput.push(string);
        }

        // efface le drapeau _12h si l'heure est <= 12
        si (
            config._a[HEURE] <= 12 &&
            getParsingFlags(config).bigHour === vrai &&
            config._a[HEURE] > 0
        ) {
            getParsingFlags(config).bigHour = non défini ;
        }

        getParsingFlags(config).parsedDateParts = config._a.slice(0);
        getParsingFlags(config).meridiem = config._meridiem;
        // gérer meridiem
        config._a[HEURE] = meridiemFixWrap(
            config._locale,
            config._a[HEURE],
            config._meridiem
        );

        // gérer l'ère
        era = getParsingFlags(config).era;
        si (ère !== null) {
            config._a[ANNÉE] = config._locale.erasConvertYear(ère, config._a[ANNÉE]);
        }

        configFromArray(config);
        checkOverflow(config);
    }

    function meridiemFixWrap(locale, hour, meridiem) {
        var isPm;

        if (meridiem == null) {
            // rien à faire
            heure de retour ;
        }
        if (locale.meridiemHeure != null) {
            return locale.meridiemHour(heure, meridiem);
        } sinon si (locale.isPM != null) {
            // Se retirer
            isPm = locale.isPM(meridiem);
            si (isPm && heure < 12) {
                heure += 12 ;
            }
            si (!isPm && heure === 12) {
                heure = 0 ;
            }
            heure de retour ;
        } autre {
            // Ceci n'est pas censé se produire
            heure de retour ;
        }
    }

    // date à partir d'une chaîne et d'un tableau de chaînes de format
    fonction configFromStringAndArray(config) {
        var tempConfig,
            meilleur moment,
            scoreToBeat,
            je,
            score actuel,
            valideFormatTrouvé,
            bestFormatIsValid = faux,
            configfLen = config._f.length ;

        si (configfLen === 0) {
            getParsingFlags(config).invalidFormat = vrai;
            config._d = nouvelle Date(NaN);
            retour;
        }

        for (i = 0; i < configfLen; i++) {
            scoreactuel = 0 ;
            validFormatFound = faux ;
            tempConfig = copyConfig({}, config);
            si (config._useUTC != null) {
                tempConfig._useUTC = config._useUTC ;
            }
            tempConfig._f = config._f[i] ;
            configFromStringAndFormat(tempConfig);

            si (est valide (tempConfig)) {
                validFormatFound = true ;
            }

            // s'il y a une entrée qui n'a pas été analysée, ajoutez une pénalité pour ce format
            currentScore += getParsingFlags(tempConfig).charsLeftOver ;

            //ou jetons
            currentScore += getParsingFlags(tempConfig).unusedTokens.length * 10;

            getParsingFlags(tempConfig).score = currentScore;

            si (!bestFormatIsValid) {
                si (
                    scoreToBeat == null ||
                    currentScore < scoreToBeat ||
                    validFormatFoundvalidFormatFound
                ) {
                    scoreToBeat = currentScore;
                    meilleurMoment = tempConfig ;
                    si (validFormatFound) {
                        bestFormatIsValid = true ;
                    }
                }
            } autre {
                if (currentScore < scoreToBeat) {
                    scoreToBeat = currentScore;
                    meilleurMoment = tempConfig ;
                }
            }
        }

        extend(config, bestMoment || tempConfig);
    }

    fonction configFromObject(config) {
        si (config._d) {
            retour;
        }

        var je = normalizeObjectUnits(config._i),
            dayOrDate = i.day === non défini ? i.date : i.jour;
        config._a = carte(
            [i.année, i.mois, jourOrDate, i.heure, i.minute, i.seconde, i.milliseconde],
            fonction (obj) {
                retourner obj && parseInt(obj, 10);
            }
        );

        configFromArray(config);
    }

    function créerDeConfig(config) {
        var res = new Moment(checkOverflow(prepareConfig(config)));
        si (res._nextDay) {
            // L'ajout est assez intelligent autour de l'heure d'été
            res.add(1, 'd');
            res._nextDay = indéfini ;
        }

        retour res;
    }

    fonction prepareConfig(config) {
        var entrée = config._i,
            format = config._f;

        config._locale = config._locale || getLocale(config._l);

        if (input === null || (format === indéfini && input === '')) {
            return createInvalid({ nullInput : vrai });
        }

        si (type d'entrée === 'chaîne') {
            config._i = entrée = config._locale.preparse(entrée);
        }

        si (estMoment(entrée)) {
            renvoie un nouveau Moment(checkOverflow(input));
        } sinon si (estDate(entrée)) {
            config._d = entrée ;
        } sinon si (estTableau(format)) {
            configFromStringAndArray(config);
        } sinon si (format) {
            configFromStringAndFormat(config);
        } autre {
            configFromInput(config);
        }

        si (! est valide (config)) {
            config._d = null ;
        }

        retourner la configuration ;
    }

    fonction configFromInput(config) {
        var entrée = config._i ;
        si (n'est pas défini (entrée)) {
            config._d = new Date(hooks.now());
        } sinon si (estDate(entrée)) {
            config._d = nouvelle Date(input.valueOf());
        } sinon si (type d'entrée === 'chaîne') {
            configFromString(config);
        } sinon si (estTableau(entrée)) {
            config._a = map(input.slice(0), function (obj) {
                return parseInt(obj, 10);
            });
            configFromArray(config);
        } sinon si (estObjet(entrée)) {
            configFromObject(config);
        } sinon si (estNombre(entrée)) {
            // à partir de millisecondes
            config._d = nouvelle Date(entrée);
        } autre {
            hooks.createFromInputFallback(config);
        }
    }

    function createLocalOrUTC(input, format, locale, strict, isUTC) {
        var c = {} ;

        si (format === vrai || format === faux) {
            strict = format ;
            format = indéfini ;
        }

        if (paramètres régionaux === vrai || paramètres régionaux === faux) {
            strict = paramètres régionaux ;
            paramètres régionaux = non défini ;
        }

        si (
            (isObject(input) && isObjectEmpty(input)) ||
            (isArray(input) && input.length === 0)
        ) {
            entrée = indéfini ;
        }
        // la construction de l'objet doit se faire de cette façon.
        // https://github.com/moment/moment/issues/1423
        c._isAMomentObject = vrai ;
        c._useUTC = c._isUTC = isUTC ;
        c._l = paramètres régionaux ;
        c._i = entrée ;
        c._f = format ;
        c._strict = strict ;

        renvoie createFromConfig(c);
    }

    function createLocal(input, format, locale, strict) {
        renvoie createLocalOrUTC(input, format, locale, strict, false);
    }

    var prototypeMin = obsolète(
            'moment().min est obsolète, utilisez moment.max à la place. http://momentjs.com/guides/#/warnings/min-max/',
            fonction () {
                var autre = createLocal.apply(null, arguments);
                if (this.isValid() && other.isValid()) {
                    retourner autre < ceci ? ceci : autre;
                } autre {
                    return createInvalid();
                }
            }
        ),
        prototypeMax = obsolète(
            'moment().max est obsolète, utilisez moment.min à la place. http://momentjs.com/guides/#/warnings/min-max/',
            fonction () {
                var autre = createLocal.apply(null, arguments);
                if (this.isValid() && other.isValid()) {
                    retour autre > ceci ? ceci : autre;
                } autre {
                    return createInvalid();
                }
            }
        );

    // Choisissez un moment m parmi les moments pour que m[fn](autre) soit vrai pour tout
    // autre. Cela repose sur la fonction fn pour être transitive.
    //
    // les moments doivent être soit un tableau d'objets moment, soit un tableau, dont
    // le premier élément est un tableau d'objets moment.
    fonction pickBy(fn, moments) {
        var res, je ;
        if (moments.length === 1 && isArray(moments[0])) {
            instants = instants[0] ;
        }
        si (!moments.longueur) {
            return createLocal();
        }
        res = moments[0] ;
        for (i = 1; i < moments.length; ++i) {
            if (!moments[i].isValid() || moments[i][fn](res)) {
                res = moments[i] ;
            }
        }
        retour res;
    }

    // À FAIRE : Utiliser [].sort à la place ?
    fonction min() {
        var args = [].slice.call(arguments, 0);

        return pickBy('isBefore', args);
    }

    fonction max() {
        var args = [].slice.call(arguments, 0);

        return pickBy('isAfter', args);
    }

    var maintenant = fonction () {
        retour Date.maintenant ? Date.maintenant() : +nouvelle Date();
    } ;

    ordre var = [
        'année',
        'trimestre',
        'mois',
        'semaine',
        'jour',
        'heure',
        'minute',
        'deuxième',
        'milliseconde',
    ] ;

    fonction estDuréeValide(m) {
        clé var,
            unitHasDecimal = faux,
            je,
            orderLen = ordering.length;
        pour (entrer en m) {
            si (
                hasOwnProp(m, clé) &&
                !(
                    indexOf.call(ordre, clé) !== -1 &&
                    (m[clé] == null || !isNaN(m[clé]))
                )
            ) {
                retourner faux ;
            }
        }

        pour (i = 0; je < orderLen; ++i) {
            si (m[ordre[i]]) {
                if (unitHasDecimal) {
                    retourner faux ; // n'autorise que les non-entiers pour la plus petite unité
                }
                if (parseFloat(m[ordering[i]]) !== toInt(m[ordering[i]])) {
                    unitHasDecimal = vrai ;
                }
            }
        }

        retourner vrai ;
    }

    la fonction est valide$1() {
        retourne this._isValid;
    }

    function createInvalid$1() {
        return createDuration(NaN);
    }

    fonction Durée(durée) {
        var normalizedInput = normalizeObjectUnits(durée),
            ans = normalizedInput.année || 0,
            trimestres = normalizedInput.trimestre || 0,
            mois = normalizedInput.mois || 0,
            semaines = normalizedInput.week || normalizedInput.isoWeek || 0,
            jours = normalizedInput.jour || 0,
            heures = normalizedInput.heure || 0,
            minutes = normalizedInput.minute || 0,
            secondes = normalizedInput.seconde || 0,
            millisecondes = normalizedInput.milliseconde || 0 ;

        this._isValid = isDurationValid(normalizedInput);

        // représentation pour dateAjouteSupprime
        ceci._millisecondes =
            + millisecondes +
            secondes * 1e3 + // 1000
            minutes * 6e4 + // 1000 * 60
            heures * 1000 * 60 * 60 ; // en utilisant 1000 * 60 * 60 au lieu de 36e5 pour éviter les erreurs d'arrondi en virgule flottante https://github.com/moment/moment/issues/2978
        // En raison de dateAddRemove traite 24 heures comme différent d'un
        // jour où nous travaillons autour de l'heure d'été, nous devons les stocker séparément
        this._days = + jours + semaines * 7 ;
        // Il est impossible de traduire des mois en jours sans savoir
        // de quels mois vous parlez, donc nous devons stocker
        // séparément.
        this._months = +mois + trimestres * 3 + années * 12 ;

        this._data = {} ;

        this._locale = getLocale();

        this._bubble();
    }

    fonction estDurée(obj) {
        renvoie obj instanceof Durée ;
    }

    fonction absRound(nombre) {
        si (nombre < 0) {
            return Math.round(-1 * nombre) * -1 ;
        } autre {
            return Math.round(nombre);
        }
    }

    // compare deux tableaux, renvoie le nombre de différences
    function compareArrays(array1, array2, dontConvert) {
        var len = Math.min(array1.length, array2.length),
            lengthDiff = Math.abs(array1.length - array2.length),
            différences = 0,
            je;
        pour (je = 0; je < longueur; je++) {
            si (
                (dontConvert && array1[i] !== array2[i]) ||
                (!dontConvert && toInt(array1[i]) !== toInt(array2[i]))
            ) {
                différences++ ;
            }
        }
        renvoie diffs + lengthDiff ;
    }

    // MISE EN PAGE

    fonction offset(token, separator) {
        addFormatToken(jeton, 0, 0, fonction () {
            var offset = this.utcOffset(),
                signe = '+';
            si (décalage < 0) {
                décalage = -décalage ;
                signe = '-';
            }
            retour (
                signe +
                zéroRemplissage(~~(décalage / 60), 2) +
                séparateur +
                zeroFill(~~offset % 60, 2)
            );
        });
    }

    offset('Z', ':');
    offset('ZZ', '');

    // PARSAGE

    addRegexToken('Z', matchShortOffset);
    addRegexToken('ZZ', matchShortOffset);
    addParseToken(['Z', 'ZZ'], fonction (entrée, tableau, config) {
        config._useUTC = vrai ;
        config._tzm = offsetFromString(matchShortOffset, input);
    });

    // AIDE

    // segmenteur de fuseau horaire
    // '+10:00' > ['10', '00']
    // '-1530' > ['-15', '30']
    var chunkOffset = /([\+\-]|\d\d)/gi ;

    fonction offsetFromString(matcher, string) {
        var matches = (string || '').match(matcher),
            tronçon,
            les pièces,
            minutes;

        si (correspond à === null) {
            renvoie nul ;
        }

        morceau = matches[matches.length - 1] || [] ;
        parts = (morceau + '').match(chunkOffset) || ['-', 0, 0] ;
        minutes = +(parties[1] * 60) + toInt(parties[2]);

        retour minute === 0 ? 0 : parties[0] === '+' ? minutes : -minutes;
    }

    // Renvoie un moment de l'entrée, c'est-à-dire local/utc/zone équivalent au modèle.
    function cloneWithOffset(input, model) {
        var res, diff ;
        si (modèle._isUTC) {
            res = modèle.clone();
            diff =
                (estMoment(entrée) || estDate(entrée)
                    ? input.valueOf()
                    : createLocal(input).valueOf()) - res.valueOf();
            // Utilise une API de bas niveau, car ce fn est une API de bas niveau.
            res._d.setTime(res._d.valueOf() + diff);
            hooks.updateOffset(res, false);
            retour res;
        } autre {
            return createLocal(input).local();
        }
    }

    fonction getDateOffset(m) {
        // Sur Firefox.24 Date#getTimezoneOffset renvoie une virgule flottante.
        // https://github.com/moment/moment/pull/1871
        return -Math.round(m._d.getTimezoneOffset());
    }

    // CROCHETS

    // Cette fonction sera appelée chaque fois qu'un moment est muté.
    // Il est destiné à maintenir le décalage synchronisé avec le fuseau horaire.
    hooks.updateOffset = fonction () {} ;

    // DES MOMENTS

    // keepLocalTime = true signifie uniquement changer le fuseau horaire, sans
    // affectant l'heure locale. Donc 5:31:26 +0300 --[utcOffset(2, vrai)]-->
    // 5:31:26 +0200 Il est possible que 5:31:26 n'existe pas avec offset
    // +0200, nous ajustons donc l'heure au besoin, pour être valide.
    //
    // Garder le temps ajoute/soustrait réellement (une heure)
    // à partir du temps réel représenté. C'est pourquoi nous appelons updateOffset
    // une seconde fois. Au cas où il voudrait que nous changions à nouveau le décalage
    // _changeInProgress == vrai cas, alors nous devons ajuster, car
    // il n'y a pas une telle heure dans le fuseau horaire donné.
    fonction getSetOffset (entrée, keepLocalTime, keepMinutes) {
        var offset = this._offset || 0,
            localAdjust ;
        si (!this.isValid()) {
            entrée de retour != null ? ceci : NaN;
        }
        si (entrée != null) {
            si (type d'entrée === 'chaîne') {
                entrée = offsetFromString(matchShortOffset, entrée);
                si (entrée === null) {
                    retournez ceci;
                }
            } else if (Math.abs(input) < 16 && !keepMinutes) {
                entrée = entrée * 60 ;
            }
            if (!this._isUTC && keepLocalTime) {
                localAdjust = getDateOffset(this);
            }
            this._offset = entrée ;
            this._isUTC = true ;
            si (localAdjust != null) {
                this.add(localAdjust, 'm');
            }
            si (décalage !== entrée) {
                si (!keepLocalTime || this._changeInProgress) {
                    ajouterSoustraire(
                        ce,
                        createDuration (entrée - décalage, 'm'),
                        1,
                        FAUX
                    );
                } sinon si (!this._changeInProgress) {
                    this._changeInProgress = vrai ;
                    hooks.updateOffset(this, true);
                    this._changeInProgress = null ;
                }
            }
            retournez ceci;
        } autre {
            retourner ceci._isUTC ? offset : getDateOffset(this);
        }
    }

    function getSetZone(input, keepLocalTime) {
        si (entrée != null) {
            si (type d'entrée !== 'chaîne') {
                entrée = -entrée ;
            }

            this.utcOffset(entrée, keepLocalTime);

            retournez ceci;
        } autre {
            return -this.utcOffset();
        }
    }

    function setOffsetToUTC(keepLocalTime) {
        retourne this.utcOffset(0, keepLocalTime);
    }

    function setOffsetToLocal(keepLocalTime) {
        si (this._isUTC) {
            this.utcOffset(0, keepLocalTime);
            this._isUTC = false ;

            si (garderHeureLocal) {
                this.subtract(getDateOffset(this), 'm');
            }
        }
        retournez ceci;
    }

    function setOffsetToParsedOffset() {
        si (this._tzm != null) {
            this.utcOffset(this._tzm, faux, vrai);
        } sinon si (typeof this._i === 'chaîne') {
            var tZone = offsetFromString(matchOffset, this._i);
            si (tZone != null) {
                this.utcOffset(tZone);
            } autre {
                this.utcOffset(0, vrai);
            }
        }
        retournez ceci;
    }

    fonction aAlignedHourOffset (entrée) {
        si (!this.isValid()) {
            retourner faux ;
        }
        entrée = entrée ? createLocal(input).utcOffset() : 0;

        retour (this.utcOffset() - entrée) % 60 === 0 ;
    }

    function isDaylightSavingTime() {
        retour (
            this.utcOffset() > this.clone().month(0).utcOffset() ||
            this.utcOffset() > this.clone().month(5).utcOffset()
        );
    }

    function isDaylightSavingTimeShifted() {
        si (!isUndefined(this._isDSTShifted)) {
            renvoie this._isDSTShifted ;
        }

        var c = {},
            autre;

        copyConfig(c, this);
        c = prepareConfig(c);

        si (c._a) {
            autre = c._isUTC ? createUTC(c._a) : createLocal(c._a);
            this._isDSTShifted =
                this.isValid() && compareArrays(c._a, other.toArray()) > 0;
        } autre {
            this._isDSTShifted = faux ;
        }

        renvoie this._isDSTShifted ;
    }

    fonction estLocal() {
        retourner this.isValid() ? !this._isUTC : faux;
    }

    fonction estUtcOffset() {
        retourner this.isValid() ? this._isUTC : faux;
    }

    fonction estUtc() {
        retourner this.isValid() ? this._isUTC && this._offset === 0 : faux;
    }

    // Regex de format de date json ASP.NET
    var aspNetRegex = /^(-|\+)?(?:(\d*)[. ])?(\d+):(\d+)(?::(\d+)(\.\d*)? )?$/,
        // depuis http://docs.closure-library.googlecode.com/git/closure_goog_date_date.js.source.html
        // un peu plus conforme à la spécification 4.4.3.2 2004, mais autorise la décimale n'importe où
        // et encore modifié pour permettre des chaînes contenant à la fois la semaine et le jour
        isoRegex =
            /^(-|\+)?P(?:([-+]?[0-9,.]*)Y)?(?:([-+]?[0-9,.]*)M )?(?:([-+]?[0-9,.]*)W)?(?:([-+]?[0-9,.]*)D)?(?:T(? :([-+]?[0-9,.]*)H)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[ 0-9,.]*)S) ?) ?$/ ;

    function createDuration(input, key) {
        var durée = entrée,
            // faire correspondre avec regexp est cher, faites-le à la demande
            match = nul,
            signe,
            ret,
            diffRes ;

        si (estDurée(entrée)) {
            durée = {
                ms : entrée._millisecondes,
                d : entrée._jours,
                M : entrée._mois,
            } ;
        } sinon si (estNombre(entrée) || !estNaN(+entrée)) {
            durée = {} ;
            si (clé) {
                durée[clé] = +entrée ;
            } autre {
                durée.millisecondes = +entrée ;
            }
        } sinon si ((match = aspNetRegex.exec(input))) {
            signe = match[1] === '-' ? -1 : 1 ;
            durée = {
                y : 0,
                d: toInt(match[DATE]) * signe,
                h: toInt(match[HOUR]) * signe,
                m : toInt(match[MINUTE]) * signe,
                s: toInt(match[SECOND]) * signe,
                ms : toInt(absRound(match[MILLISECOND] * 1000)) * signe, // la virgule décimale de la milliseconde est incluse dans la correspondance
            } ;
        } sinon si ((match = isoRegex.exec(input))) {
            signe = match[1] === '-' ? -1 : 1 ;
            durée = {
                y : parseIso(match[2], signe),
                M : parseIso(match[3], signe),
                w : parseIso(match[4], signe),
                d : parseIso(match[5], signe),
                h : parseIso(match[6], signe),
                m : parseIso(match[7], signe),
                s : parseIso(match[8], signe),
            } ;
        } sinon si (durée == null) {
            // recherche null ou indéfini
            durée = {} ;
        } sinon si (
            typede durée === 'objet' &&
            ('de' dans la durée || 'à' dans la durée)
        ) {
            diffRes = momentsDifférence(
                createLocal(duration.from),
                createLocal(duration.to)
            );

            durée = {} ;
            durée.ms = diffRes.millisecondes ;
            durée.M = diffRes.mois ;
        }

        ret = nouvelle Durée(durée);

        if (isDuration(input) && hasOwnProp(input, '_locale')) {
            ret._locale = input._locale ;
        }

        if (isDuration(input) && hasOwnProp(input, '_isValid')) {
            ret._isValid = input._isValid ;
        }

        retour ret;
    }

    createDuration.fn = Durée.prototype ;
    createDuration.invalid = createInvalid$1 ;

    function parseIso(inp, signe) {
        // Nous utiliserions normalement ~~inp pour cela, mais malheureusement aussi
        // convertit les flottants en entiers.
        // inp peut être indéfini, donc faites attention en appelant replace dessus.
        var res = inp && parseFloat(inp.replace(',', '.'));
        // applique le signe tant qu'on y est
        return (isNaN(res) ? 0 : res) * signe;
    }

    function positiveMomentsDifference(base, autre) {
        var res = {} ;

        res.mois =
            autre.mois() - base.mois() + (autre.année() - base.année()) * 12 ;
        si (base.clone().add(res.mois, 'M').isAfter(autre)) {
            --res.mois;
        }

        res.millisecondes = +autre - +base.clone().add(res.mois, 'M');

        retour res;
    }

    fonction momentsDifférence(base, autre) {
        var res ;
        if (!(base.isValid() && other.isValid())) {
            return { millisecondes : 0, mois : 0 } ;
        }

        autre = cloneWithOffset(autre, base);
        si (base.isBefore(autre)) {
            res = positiveMomentsDifference(base, autre);
        } autre {
            res = positiveMomentsDifference(autre, base);
            res.millisecondes = -res.millisecondes ;
            res.mois = -res.mois ;
        }

        retour res;
    }

    // TODO : supprimer l'argument 'name' après la suppression de la dépréciation
    function createAdder(direction, nom) {
        fonction de retour (val, période) {
            var dur, tmp;
            // inverser les arguments, mais s'en plaindre
            if (période !== null && !isNaN(+période)) {
                obsolèteSimple(
                    nom,
                    'moment().' +
                        nom +
                        '(point, nombre) est obsolète. Veuillez utiliser moment().' +
                        nom +
                        '(nombre, point). ' +
                        'Voir http://momentjs.com/guides/#/warnings/add-inverted-param/ pour plus d'informations.'
                );
                tmp = val;
                val = période ;
                période = tmp ;
            }

            dur = createDuration(val, period);
            addSubtract(this, dur, direction);
            retournez ceci;
        } ;
    }

    function addSubtract(mom, duration, isAdding, updateOffset) {
        var millisecondes = durée._millisecondes,
            jours = absRound(duration._days),
            mois = absRound(durée._mois);

        si (!mom.isValid()) {
            // Pas d'opération
            retour;
        }

        updateOffset = updateOffset == null ? true : updateOffset ;

        si (mois) {
            setMonth(maman, get(maman, 'Month') + mois * isAdding);
        }
        si (jours) {
            set$1(mom, 'Date', get(mom, 'Date') + days * isAdding);
        }
        si (millisecondes) {
            mom._d.setTime(mom._d.valueOf() + millisecondes * isAdding);
        }
        si (mise à jourOffset) {
            hooks.updateOffset(maman, jours || mois);
        }
    }

    var add = createAdder(1, 'add'),
        soustraire = createAdder(-1, 'soustraire');

    fonction estChaîne(entrée) {
        return typeof input === 'string' || instance d'entrée de String ;
    }

    // type MomentInput = Moment | Rendez-vous | chaîne | nombre | (nombre | chaîne)[] | MomentInputObject | annuler; // nul | indéfini
    function isMomentInput(input) {
        retour (
            estMoment(entrée) ||
            estDate(entrée) ||
            estChaîne(entrée) ||
            estNuméro(entrée) ||
            estNumberOrStringArray(entrée) ||
            isMomentInputObject(entrée) ||
            entrée === null ||
            entrée === indéfini
        );
    }

    function isMomentInputObject(input) {
        var objectTest = isObject(input) && !isObjectEmpty(input),
            propriétéTest = faux,
            propriétés = [
                'années',
                'année',
                'y',
                'mois',
                'mois',
                'M',
                'jours',
                'jour',
                'd',
                'Rendez-vous',
                'date',
                'D',
                'heures',
                'heure',
                'h',
                'minutes',
                'minute',
                'moi',
                'secondes',
                'deuxième',
                's',
                'millisecondes',
                'milliseconde',
                'SP',
            ],
            je,
            propriété,
            propertyLen = properties.length;

        pour (je = 0; je < propriétéLen; je += 1) {
            propriété = propriétés[i] ;
            propriétéTest = propriétéTest || hasOwnProp (entrée, propriété);
        }

        renvoie objectTest && propertyTest;
    }

    function isNumberOrStringArray(input) {
        var arrayTest = isArray(input),
            dataTypeTest = faux ;
        si (tableauTest) {
            testTypeDonnées =
                input.filter(fonction (élément) {
                    return !isNumber(item) && isString(input);
                }).length === 0;
        }
        return arrayTest && dataTypeTest;
    }

    function isCalendarSpec(input) {
        var objectTest = isObject(input) && !isObjectEmpty(input),
            propriétéTest = faux,
            propriétés = [
                'même jour',
                'le prochain jour',
                'dernier jour',
                'la semaine prochaine',
                'la semaine dernière',
                'sameElse',
            ],
            je,
            propriété;

        pour (je = 0; je < propriétés.longueur; je += 1) {
            propriété = propriétés[i] ;
            propriétéTest = propriétéTest || hasOwnProp (entrée, propriété);
        }

        renvoie objectTest && propertyTest;
    }

    function getCalendarFormat(monMoment, maintenant) {
        var diff = myMoment.diff(now, 'days', true);
        retour diff < -6
            ? 'sameElse'
            : écart < -1
            ? 'la semaine dernière'
            : diff < 0
            ? 'dernier jour'
            : écart < 1
            ? 'même jour'
            : écart < 2
            ? 'le prochain jour'
            : écart < 7
            ? 'la semaine prochaine'
            : 'sameElse';
    }

    fonction calendrier$1(heure, formats) {
        // Prise en charge d'un seul paramètre, les formats ne surchargent que la fonction de calendrier
        si (arguments.length === 1) {
            si (!arguments[0]) {
                temps = indéfini ;
                formats = non défini ;
            } sinon si (isMomentInput(arguments[0])) {
                temps = arguments[0] ;
                formats = non défini ;
            } sinon si (isCalendarSpec(arguments[0])) {
                formats = arguments[0] ;
                temps = indéfini ;
            }
        }
        // Nous voulons comparer le début d'aujourd'hui avec celui-ci.
        // L'obtention du début d'aujourd'hui dépend du fait que nous soyons local/utc/offset ou non.
        var maintenant = temps || créerLocal(),
            sod = cloneWithOffset(maintenant, ceci).startOf('day'),
            format = hooks.calendarFormat(this, sod) || 'sameElse',
            sortie =
                formats &&
                (estFonction(formats[format])
                    ? formats[format].appel(ceci, maintenant)
                    : format[format]);

        retourne this.format(
            sortie || this.localeData().calendar(format, this, createLocal(now))
        );
    }

    fonction clone() {
        retourner un nouveau Moment(this);
    }

    fonction estAprès(entrée, unités) {
        var entréelocale = estMoment(entrée) ? entrée : createLocal(entrée);
        if (!(this.isValid() && localInput.isValid())) {
            retourner faux ;
        }
        unités = normalizeUnits(unités) || 'milliseconde';
        si (unités === 'milliseconde') {
            renvoie this.valueOf() > localInput.valueOf();
        } autre {
            return localInput.valueOf() < this.clone().startOf(units).valueOf();
        }
    }

    fonction isBefore(entrée, unités) {
        var entréelocale = estMoment(entrée) ? entrée : createLocal(entrée);
        if (!(this.isValid() && localInput.isValid())) {
            retourner faux ;
        }
        unités = normalizeUnits(unités) || 'milliseconde';
        si (unités === 'milliseconde') {
            return this.valueOf() < localInput.valueOf();
        } autre {
            return this.clone().endOf(units).valueOf() < localInput.valueOf();
        }
    }

    function isBetween(de, à, unités, inclusivité) {
        var localFrom = estMoment(depuis) ​​? de : créerLocal(de),
            localTo = estMoment(vers) ? à : createLocal(à);
        if (!(this.isValid() && localFrom.isValid() && localTo.isValid())) {
            retourner faux ;
        }
        inclusivité = inclusivité || '()';
        retour (
            (inclusivité[0] === '('
                ? this.isAfter(localFrom, unités)
                : !this.isBefore(localFrom, unités)) &&
            (inclusivité[1] === ')'
                ? this.isBefore(localTo, unités)
                : !this.isAfter(localTo, unités))
        );
    }

    function isSame(input, units) {
        var entréelocale = estMoment(entrée) ? entrée : createLocal(entrée),
            inputMs ;
        if (!(this.isValid() && localInput.isValid())) {
            retourner faux ;
        }
        unités = normalizeUnits(unités) || 'milliseconde';
        si (unités === 'milliseconde') {
            return this.valueOf() === localInput.valueOf();
        } autre {
            inputMs = localInput.valueOf();
            retour (
                this.clone().startOf(units).valueOf() <= inputMs &&
                inputMs <= this.clone().endOf(units).valueOf()
            );
        }
    }

    function isSameOrAfter(input, units) {
        return this.isSame(input, units) || this.isAfter(input, units);
    }

    function isSameOrBefore(input, units) {
        return this.isSame(input, units) || this.isBefore(entrée, unités);
    }

    fonction diff(entrée, unités, asFloat) {
        var cela, zoneDelta, sortie ;

        si (!this.isValid()) {
            renvoie NaN ;
        }

        that = cloneWithOffset(input, this);

        si (!c'est valide()) {
            renvoie NaN ;
        }

        zoneDelta = (that.utcOffset() - this.utcOffset()) * 6e4;

        unités = normalizeUnits(unités);

        changer (unités) {
            cas 'année' :
                sortie = monthDiff(this, that) / 12 ;
                casser;
            cas 'mois' :
                output = monthDiff(this, that);
                casser;
            cas 'quart' :
                sortie = monthDiff(this, that) / 3 ;
                casser;
            cas 'deuxième' :
                sortie = (ceci - cela) / 1e3 ;
                casser; // 1000
            cas 'minute' :
                sortie = (ceci - cela) / 6e4 ;
                casser; // 1000 * 60
            cas 'heure' :
                sortie = (ceci - cela) / 36e5 ;
                casser; // 1000 * 60 * 60
            cas 'jour' :
                sortie = (ceci - cela - zoneDelta) / 864e5 ;
                casser; // 1000 * 60 * 60 * 24, annuler dst
            cas 'semaine' :
                sortie = (ceci - cela - zoneDelta) / 6048e5 ;
                casser; // 1000 * 60 * 60 * 24 * 7, annuler dst
            défaut:
                sortie = ceci - cela ;
        }

        retour commeFlottant ? sortie : absFloor(sortie);
    }

    fonction moisDiff(a, b) {
        si (a.date() < b.date()) {
            // les calculs de fin de mois fonctionnent correctement lorsque le mois de début a plus
            // jours que le mois de fin.
            return -monthDiff(b, a);
        }
        // différence en mois
        var wholeMonthDiff = (b.year() - a.year()) * 12 + (b.month() - a.month()),
            // b est dans (ancre - 1 mois, ancre + 1 mois)
            ancre = a.clone().add(wholeMonthDiff, 'mois'),
            ancre2,
            ajuster;

        si (b - ancre < 0) {
            anchor2 = a.clone().add(wholeMonthDiff - 1, 'mois');
            // linéaire sur le mois
            ajuster = (b - ancre) / (ancre - ancre2);
        } autre {
            anchor2 = a.clone().add(wholeMonthDiff + 1, 'mois');
            // linéaire sur le mois
            ajuster = (b - ancre) / (ancre2 - ancre);
        }

        //vérifie le zéro négatif, renvoie zéro si zéro négatif
        return -(wholeMonthDiff + ajuster) || 0 ;
    }

    hooks.defaultFormat = 'AAAA-MM-JJTHH:mm:ssZ';
    hooks.defaultFormatUtc = 'AAAA-MM-JJTHH:mm:ss[Z]';

    function toString() {
        return this.clone().locale('en').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
    }

    function toISOString(keepOffset) {
        si (!this.isValid()) {
            renvoie nul ;
        }
        var utc = keepOffset !== vrai,
            m = utc ? this.clone().utc() : this;
        si (mois.année() < 0 || mois.année() > 9999) {
            format de retour Moment(
                moi,
                UTC
                    ? 'AAAAAA-MM-JJ[T]HH:mm:ss.SSS[Z]'
                    : 'AAAAAA-MM-JJ[T]HH:mm:ss.SSSZ'
            );
        }
        si (isFunction(Date.prototype.toISOString)) {
            // l'implémentation native est ~50x plus rapide, utilisez-la quand nous le pouvons
            si (utc) {
                retourne this.toDate().toISOString();
            } autre {
                renvoie une nouvelle date (this.valueOf() + this.utcOffset() * 60 * 1000)
                    .toISOString()
                    .replace('Z', formatMoment(m, 'Z'));
            }
        }
        format de retour Moment(
            moi,
            UTC ? 'AAAA-MM-JJ[T]HH:mm:ss.SSS[Z]' : 'AAAA-MM-JJ[T]HH:mm:ss.SSSZ'
        );
    }

    /**
     * Renvoyez une représentation lisible par l'homme d'un moment qui peut
     * également être évalué pour obtenir un nouveau moment qui est le même
     *
     * @lien https://nodejs.org/dist/latest/docs/api/util.html#util_custom_inspect_function_on_objects
     */
    fonction inspecter() {
        si (!this.isValid()) {
            return 'moment.invalid(/* ' + this._i + ' */)' ;
        }
        var fonction = 'moment',
            zone = '',
            préfixe,
            année,
            date-heure,
            suffixe;
        si (!this.isLocal()) {
            func = this.utcOffset() === 0 ? 'moment.utc' : 'moment.parseZone';
            zone = 'Z';
        }
        préfixe = '[' + fonction + '("]' ;
        année = 0 <= cette.année() && cette.année() <= 9999 ? 'AAAA' : 'AAAAAAA' ;
        datetime = '-MM-JJ[T]HH:mm:ss.SSS' ;
        suffixe = zone + '[")]' ;

        return this.format(prefix + year + datetime + suffix);
    }

    format de fonction (chaîne d'entrée) {
        si (!chaîne d'entrée) {
            inputString = this.isUtc()
                ? hooks.defaultFormatUtc
                : hooks.defaultFormat;
        }
        var output = formatMoment(this, inputString);
        renvoie this.localeData().postformat(output);
    }

    fonction de (heure, sans suffixe) {
        si (
            this.isValid() &&
            ((isMoment(time) && time.isValid()) || createLocal(time).isValid())
        ) {
            return createDuration({ to : this, from: time })
                .locale(this.locale())
                .humanize(!sansSuffixe);
        } autre {
            renvoie this.localeData().invalidDate();
        }
    }

    fonction à partir de maintenant (sans suffixe) {
        return this.from(createLocal(), withoutSuffix);
    }

    function to(time, withoutSuffix) {
        si (
            this.isValid() &&
            ((isMoment(time) && time.isValid()) || createLocal(time).isValid())
        ) {
            return createDuration({ from: this, to: time })
                .locale(this.locale())
                .humanize(!sansSuffixe);
        } autre {
            renvoie this.localeData().invalidDate();
        }
    }

    fonction maintenant (sans suffixe) {
        return this.to(createLocal(), withoutSuffix);
    }

    // Si une clé locale est passée, il définira la locale pour ce
    // exemple. Sinon, il renverra la configuration locale
    // variables pour cette instance.
    fonction locale (clé) {
        var newLocaleData ;

        si (clé === indéfini) {
            retourne this._locale._abbr ;
        } autre {
            newLocaleData = getLocale(clé);
            if (newLocaleData != null) {
                this._locale = newLocaleData ;
            }
            retournez ceci;
        }
    }

    var lang = obsolète(
        'moment().lang() est obsolète. Utilisez plutôt moment().localeData() pour obtenir la configuration de la langue. Utilisez moment().locale() pour changer de langue.',
        clé de fonction) {
            si (clé === indéfini) {
                return this.localeData();
            } autre {
                return this.locale(key);
            }
        }
    );

    fonction localeDonnées() {
        retourne this._locale;
    }

    var MS_PER_SECOND = 1000,
        MS_PER_MINUTE = 60 * MS_PER_SECOND,
        MS_PER_HOUR = 60 * MS_PER_MINUTE,
        MS_PER_400_YEARS = (365 * 400 + 97) * 24 * MS_PER_HOUR ;

    // modulo réel - gère les nombres négatifs (pour les dates avant 1970) :
    fonction mod$1(dividende, diviseur) {
        rendement ((dividende % diviseur) + diviseur) % diviseur ;
    }

    function localStartOfDate(y, m, d) {
        // le constructeur de date remappe les années 0-99 à 1900-1999
        si (y < 100 && y >= 0) {
            // conserver les années bissextiles en utilisant un cycle complet de 400 ans, puis réinitialiser
            renvoie une nouvelle date (a + 400, m, j) - MS_PER_400_YEARS ;
        } autre {
            renvoie une nouvelle Date(y, m, d).valueOf();
        }
    }

    fonction utcStartOfDate(y, m, d) {
        // Date.UTC remappe les années 0-99 à 1900-1999
        si (y < 100 && y >= 0) {
            // conserver les années bissextiles en utilisant un cycle complet de 400 ans, puis réinitialiser
            retourner Date.UTC(a + 400, m, j) - MS_PER_400_YEARS ;
        } autre {
            return Date.UTC(a, m, j);
        }
    }

    fonction startOf(unités) {
        var heure, startOfDate ;
        unités = normalizeUnits(unités);
        if (units === indéfini || units === 'milliseconde' || !this.isValid()) {
            retournez ceci;
        }

        startOfDate = this._isUTC ? utcStartOfDate : localStartOfDate ;

        changer (unités) {
            cas 'année' :
                heure = startOfDate(this.year(), 0, 1);
                casser;
            cas 'quart' :
                heure = startOfDate(
                    cette année(),
                    ce.mois() - (ce.mois() % 3),
                    1
                );
                casser;
            cas 'mois' :
                heure = startOfDate(cette.année(), ce.mois(), 1);
                casser;
            cas 'semaine' :
                heure = startOfDate(
                    cette année(),
                    ce mois-ci(),
                    this.date() - this.weekday()
                );
                casser;
            cas 'isoWeek' :
                heure = startOfDate(
                    cette année(),
                    ce mois-ci(),
                    this.date() - (this.isoWeekday() - 1)
                );
                casser;
            cas 'jour' :
            cas 'date' :
                heure = startOfDate(cette.année(), ce.mois(), cette.date());
                casser;
            cas 'heure' :
                temps = this._d.valueOf();
                temps -= mod$1(
                    temps + (this._isUTC ? 0 : this.utcOffset() * MS_PER_MINUTE),
                    MS_PER_HOUR
                );
                casser;
            cas 'minute' :
                temps = this._d.valueOf();
                heure -= mod$1(heure, MS_PER_MINUTE);
                casser;
            cas 'deuxième' :
                temps = this._d.valueOf();
                temps -= mod$1(temps, MS_PER_SECOND);
                casser;
        }

        this._d.setTime(time);
        hooks.updateOffset(this, true);
        retournez ceci;
    }

    fonction endOf(unités) {
        var heure, startOfDate ;
        unités = normalizeUnits(unités);
        if (units === indéfini || units === 'milliseconde' || !this.isValid()) {
            retournez ceci;
        }

        startOfDate = this._isUTC ? utcStartOfDate : localStartOfDate ;

        changer (unités) {
            cas 'année' :
                heure = startOfDate(this.year() + 1, 0, 1) - 1 ;
                casser;
            cas 'quart' :
                temps =
                    débutDeDate(
                        cette année(),
                        ce.mois() - (ce.mois() % 3) + 3,
                        1
                    ) - 1;
                casser;
            cas 'mois' :
                heure = startOfDate(cette.année(), ce.mois() + 1, 1) - 1 ;
                casser;
            cas 'semaine' :
                temps =
                    débutDeDate(
                        cette année(),
                        ce mois-ci(),
                        this.date() - this.weekday() + 7
                    ) - 1;
                casser;
            cas 'isoWeek' :
                temps =
                    débutDeDate(
                        cette année(),
                        ce mois-ci(),
                        this.date() - (this.isoWeekday() - 1) + 7
                    ) - 1;
                casser;
            cas 'jour' :
            cas 'date' :
                heure = startOfDate(cette.année(), ce.mois(), cette.date() + 1) - 1 ;
                casser;
            cas 'heure' :
                temps = this._d.valueOf();
                temps +=
                    MS_PER_HOUR -
                    module$1(
                        temps + (this._isUTC ? 0 : this.utcOffset() * MS_PER_MINUTE),
                        MS_PER_HOUR
                    ) -
                    1;
                casser;
            cas 'minute' :
                temps = this._d.valueOf();
                temps += MS_PER_MINUTE - mod$1(temps, MS_PER_MINUTE) - 1 ;
                casser;
            cas 'deuxième' :
                temps = this._d.valueOf();
                temps += MS_PER_SECOND - mod$1(temps, MS_PER_SECOND) - 1 ;
                casser;
        }

        this._d.setTime(time);
        hooks.updateOffset(this, true);
        retournez ceci;
    }

    fonction valueOf() {
        retourner this._d.valueOf() - (this._offset || 0) * 60000 ;
    }

    fonction unix() {
        return Math.floor(this.valueOf() / 1000);
    }

    fonction àDate() {
        renvoie une nouvelle Date(this.valueOf());
    }

    fonction versTableau() {
        var m = ceci ;
        retour [
            mon oreille(),
            m.mois(),
            m.date(),
            m.heure(),
            m.minute(),
            m.seconde(),
            m.milliseconde(),
        ] ;
    }

    fonction versObjet() {
        var m = ceci ;
        retour {
            years: m.year(),
            months: m.month(),
            date: m.date(),
            hours: m.hours(),
            minutes: m.minutes(),
            seconds: m.seconds(),
            milliseconds: m.milliseconds(),
        };
    }

    function toJSON() {
        // new Date(NaN).toJSON() === null
        return this.isValid() ? this.toISOString() : null;
    }

    function isValid$2() {
        return isValid(this);
    }

    function parsingFlags() {
        return extend({}, getParsingFlags(this));
    }

    function invalidAt() {
        return getParsingFlags(this).overflow;
    }

    function creationData() {
        return {
            input: this._i,
            format: this._f,
            locale: this._locale,
            isUTC: this._isUTC,
            strict: this._strict,
        };
    }

    addFormatToken('N', 0, 0, 'eraAbbr');
    addFormatToken('NN', 0, 0, 'eraAbbr');
    addFormatToken('NNN', 0, 0, 'eraAbbr');
    addFormatToken('NNNN', 0, 0, 'eraName');
    addFormatToken('NNNNN', 0, 0, 'eraNarrow');

    addFormatToken('y', ['y', 1], 'yo', 'eraYear');
    addFormatToken('y', ['yy', 2], 0, 'eraYear');
    addFormatToken('y', ['yyy', 3], 0, 'eraYear');
    addFormatToken('y', ['yyyy', 4], 0, 'eraYear');

    addRegexToken('N', matchEraAbbr);
    addRegexToken('NN', matchEraAbbr);
    addRegexToken('NNN', matchEraAbbr);
    addRegexToken('NNNN', matchEraName);
    addRegexToken('NNNNN', matchEraNarrow);

    addParseToken(
        ['N', 'NN', 'NNN', 'NNNN', 'NNNNN'],
        function (input, array, config, token) {
            var era = config._locale.erasParse(input, token, config._strict);
            if (era) {
                getParsingFlags(config).era = era;
            } else {
                getParsingFlags(config).invalidEra = input;
            }
        }
    );

    addRegexToken('y', matchUnsigned);
    addRegexToken('yy', matchUnsigned);
    addRegexToken('yyy', matchUnsigned);
    addRegexToken('yyyy', matchUnsigned);
    addRegexToken('yo', matchEraYearOrdinal);

    addParseToken(['y', 'yy', 'yyy', 'yyyy'], YEAR);
    addParseToken(['yo'], function (input, array, config, token) {
        var match;
        if (config._locale._eraYearOrdinalRegex) {
            match = input.match(config._locale._eraYearOrdinalRegex);
        }

        if (config._locale.eraYearOrdinalParse) {
            array[YEAR] = config._locale.eraYearOrdinalParse(input, match);
        } else {
            array[YEAR] = parseInt(input, 10);
        }
    });

    function localeEras(m, format) {
        var i,
            l,
            date,
            eras = this._eras || getLocale('en')._eras;
        for (i = 0, l = eras.length; i < l; ++i) {
            switch (typeof eras[i].since) {
                case 'string':
                    // truncate time
                    date = hooks(eras[i].since).startOf('day');
                    eras[i].since = date.valueOf();
                    break;
            }

            switch (typeof eras[i].until) {
                case 'undefined':
                    eras[i].until = +Infinity;
                    break;
                case 'string':
                    // truncate time
                    date = hooks(eras[i].until).startOf('day').valueOf();
                    eras[i].until = date.valueOf();
                    break;
            }
        }
        return eras;
    }

    function localeErasParse(eraName, format, strict) {
        var i,
            l,
            eras = this.eras(),
            name,
            abbr,
            narrow;
        eraName = eraName.toUpperCase();

        for (i = 0, l = eras.length; i < l; ++i) {
            name = eras[i].name.toUpperCase();
            abbr = eras[i].abbr.toUpperCase();
            narrow = eras[i].narrow.toUpperCase();

            if (strict) {
                switch (format) {
                    case 'N':
                    case 'NN':
                    case 'NNN':
                        if (abbr === eraName) {
                            return eras[i];
                        }
                        break;

                    case 'NNNN':
                        if (name === eraName) {
                            return eras[i];
                        }
                        break;

                    case 'NNNNN':
                        if (narrow === eraName) {
                            return eras[i];
                        }
                        break;
                }
            } else if ([name, abbr, narrow].indexOf(eraName) >= 0) {
                return eras[i];
            }
        }
    }

    function localeErasConvertYear(era, year) {
        var dir = era.since <= era.until ? +1 : -1;
        if (year === undefined) {
            return hooks(era.since).year();
        } else {
            return hooks(era.since).year() + (year - era.offset) * dir;
        }
    }

    function getEraName() {
        var i,
            l,
            val,
            eras = this.localeData().eras();
        for (i = 0, l = eras.length; i < l; ++i) {
            // truncate time
            val = this.clone().startOf('day').valueOf();

            if (eras[i].since <= val && val <= eras[i].until) {
                return eras[i].name;
            }
            if (eras[i].until <= val && val <= eras[i].since) {
                return eras[i].name;
            }
        }

        return '';
    }

    function getEraNarrow() {
        var i,
            l,
            val,
            eras = this.localeData().eras();
        for (i = 0, l = eras.length; i < l; ++i) {
            // truncate time
            val = this.clone().startOf('day').valueOf();

            if (eras[i].since <= val && val <= eras[i].until) {
                return eras[i].narrow;
            }
            if (eras[i].until <= val && val <= eras[i].since) {
                return eras[i].narrow;
            }
        }

        return '';
    }

    function getEraAbbr() {
        var i,
            l,
            val,
            eras = this.localeData().eras();
        for (i = 0, l = eras.length; i < l; ++i) {
            // truncate time
            val = this.clone().startOf('day').valueOf();

            if (eras[i].since <= val && val <= eras[i].until) {
                return eras[i].abbr;
            }
            if (eras[i].until <= val && val <= eras[i].since) {
                return eras[i].abbr;
            }
        }

        return '';
    }

    function getEraYear() {
        var i,
            l,
            dir,
            val,
            eras = this.localeData().eras();
        for (i = 0, l = eras.length; i < l; ++i) {
            dir = eras[i].since <= eras[i].until ? +1 : -1;

            // truncate time
            val = this.clone().startOf('day').valueOf();

            if (
                (eras[i].since <= val && val <= eras[i].until) ||
                (eras[i].until <= val && val <= eras[i].since)
            ) {
                return (
                    (this.year() - hooks(eras[i].since).year()) * dir +
                    eras[i].offset
                );
            }
        }

        return this.year();
    }

    function erasNameRegex(isStrict) {
        if (!hasOwnProp(this, '_erasNameRegex')) {
            computeErasParse.call(this);
        }
        return isStrict ? this._erasNameRegex : this._erasRegex;
    }

    function erasAbbrRegex(isStrict) {
        if (!hasOwnProp(this, '_erasAbbrRegex')) {
            computeErasParse.call(this);
        }
        return isStrict ? this._erasAbbrRegex : this._erasRegex;
    }

    function erasNarrowRegex(isStrict) {
        if (!hasOwnProp(this, '_erasNarrowRegex')) {
            computeErasParse.call(this);
        }
        return isStrict ? this._erasNarrowRegex : this._erasRegex;
    }

    function matchEraAbbr(isStrict, locale) {
        return locale.erasAbbrRegex(isStrict);
    }

    function matchEraName(isStrict, locale) {
        return locale.erasNameRegex(isStrict);
    }

    function matchEraNarrow(isStrict, locale) {
        return locale.erasNarrowRegex(isStrict);
    }

    function matchEraYearOrdinal(isStrict, locale) {
        return locale._eraYearOrdinalRegex || matchUnsigned;
    }

    function computeErasParse() {
        var abbrPieces = [],
            namePieces = [],
            narrowPieces = [],
            mixedPieces = [],
            i,
            l,
            eras = this.eras();

        for (i = 0, l = eras.length; i < l; ++i) {
            namePieces.push(regexEscape(eras[i].name));
            abbrPieces.push(regexEscape(eras[i].abbr));
            narrowPieces.push(regexEscape(eras[i].narrow));

            mixedPieces.push(regexEscape(eras[i].name));
            mixedPieces.push(regexEscape(eras[i].abbr));
            mixedPieces.push(regexEscape(eras[i].narrow));
        }

        this._erasRegex = new RegExp('^(' + mixedPieces.join('|') + ')', 'i');
        this._erasNameRegex = new RegExp('^(' + namePieces.join('|') + ')', 'i');
        this._erasAbbrRegex = new RegExp('^(' + abbrPieces.join('|') + ')', 'i');
        this._erasNarrowRegex = new RegExp(
            '^(' + narrowPieces.join('|') + ')',
            'i'
        );
    }

    // FORMATTING

    addFormatToken(0, ['gg', 2], 0, function () {
        return this.weekYear() % 100;
    });

    addFormatToken(0, ['GG', 2], 0, function () {
        return this.isoWeekYear() % 100;
    });

    function addWeekYearFormatToken(token, getter) {
        addFormatToken(0, [token, token.length], 0, getter);
    }

    addWeekYearFormatToken('gggg', 'weekYear');
    addWeekYearFormatToken('ggggg', 'weekYear');
    addWeekYearFormatToken('GGGG', 'isoWeekYear');
    addWeekYearFormatToken('GGGGG', 'isoWeekYear');

    // ALIASES

    addUnitAlias('weekYear', 'gg');
    addUnitAlias('isoWeekYear', 'GG');

    // PRIORITY

    addUnitPriority('weekYear', 1);
    addUnitPriority('isoWeekYear', 1);

    // PARSING

    addRegexToken('G', matchSigned);
    addRegexToken('g', matchSigned);
    addRegexToken('GG', match1to2, match2);
    addRegexToken('gg', match1to2, match2);
    addRegexToken('GGGG', match1to4, match4);
    addRegexToken('gggg', match1to4, match4);
    addRegexToken('GGGGG', match1to6, match6);
    addRegexToken('ggggg', match1to6, match6);

    addWeekParseToken(
        ['gggg', 'ggggg', 'GGGG', 'GGGGG'],
        function (input, week, config, token) {
            week[token.substr(0, 2)] = toInt(input);
        }
    );

    addWeekParseToken(['gg', 'GG'], function (input, week, config, token) {
        week[token] = hooks.parseTwoDigitYear(input);
    });

    // MOMENTS

    function getSetWeekYear(input) {
        return getSetWeekYearHelper.call(
            this,
            input,
            this.week(),
            this.weekday(),
            this.localeData()._week.dow,
            this.localeData()._week.doy
        );
    }

    function getSetISOWeekYear(input) {
        return getSetWeekYearHelper.call(
            this,
            input,
            this.isoWeek(),
            this.isoWeekday(),
            1,
            4
        );
    }

    function getISOWeeksInYear() {
        return weeksInYear(this.year(), 1, 4);
    }

    function getISOWeeksInISOWeekYear() {
        return weeksInYear(this.isoWeekYear(), 1, 4);
    }

    function getWeeksInYear() {
        var weekInfo = this.localeData()._week;
        return weeksInYear(this.year(), weekInfo.dow, weekInfo.doy);
    }

    function getWeeksInWeekYear() {
        var weekInfo = this.localeData()._week;
        return weeksInYear(this.weekYear(), weekInfo.dow, weekInfo.doy);
    }

    function getSetWeekYearHelper(input, week, weekday, dow, doy) {
        var weeksTarget;
        if (input == null) {
            return weekOfYear(this, dow, doy).year;
        } else {
            weeksTarget = weeksInYear(input, dow, doy);
            if (week > weeksTarget) {
                week = weeksTarget;
            }
            return setWeekAll.call(this, input, week, weekday, dow, doy);
        }
    }

    function setWeekAll(weekYear, week, weekday, dow, doy) {
        var dayOfYearData = dayOfYearFromWeeks(weekYear, week, weekday, dow, doy),
            date = createUTCDate(dayOfYearData.year, 0, dayOfYearData.dayOfYear);

        this.year(date.getUTCFullYear());
        this.month(date.getUTCMonth());
        this.date(date.getUTCDate());
        return this;
    }

    // FORMATTING

    addFormatToken('Q', 0, 'Qo', 'quarter');

    // ALIASES

    addUnitAlias('quarter', 'Q');

    // PRIORITY

    addUnitPriority('quarter', 7);

    // PARSING

    addRegexToken('Q', match1);
    addParseToken('Q', function (input, array) {
        array[MONTH] = (toInt(input) - 1) * 3;
    });

    // MOMENTS

    function getSetQuarter(input) {
        return input == null
            ? Math.ceil((this.month() + 1) / 3)
            : this.month((input - 1) * 3 + (this.month() % 3));
    }

    // FORMATTING

    addFormatToken('D', ['DD', 2], 'Do', 'date');

    // ALIASES

    addUnitAlias('date', 'D');

    // PRIORITY
    addUnitPriority('date', 9);

    // PARSING

    addRegexToken('D', match1to2);
    addRegexToken('DD', match1to2, match2);
    addRegexToken('Do', function (isStrict, locale) {
        // TODO: Remove "ordinalParse" fallback in next major release.
        return isStrict
            ? locale._dayOfMonthOrdinalParse || locale._ordinalParse
            : locale._dayOfMonthOrdinalParseLenient;
    });

    addParseToken(['D', 'DD'], DATE);
    addParseToken('Do', function (input, array) {
        array[DATE] = toInt(input.match(match1to2)[0]);
    });

    // MOMENTS

    var getSetDayOfMonth = makeGetSet('Date', true);

    // FORMATTING

    addFormatToken('DDD', ['DDDD', 3], 'DDDo', 'dayOfYear');

    // ALIASES

    addUnitAlias('dayOfYear', 'DDD');

    // PRIORITY
    addUnitPriority('dayOfYear', 4);

    // PARSING

    addRegexToken('DDD', match1to3);
    addRegexToken('DDDD', match3);
    addParseToken(['DDD', 'DDDD'], function (input, array, config) {
        config._dayOfYear = toInt(input);
    });

    // HELPERS

    // MOMENTS

    function getSetDayOfYear(input) {
        var dayOfYear =
            Math.round(
                (this.clone().startOf('day') - this.clone().startOf('year')) / 864e5
            ) + 1;
        return input == null ? dayOfYear : this.add(input - dayOfYear, 'd');
    }

    // FORMATTING

    addFormatToken('m', ['mm', 2], 0, 'minute');

    // ALIASES

    addUnitAlias('minute', 'm');

    // PRIORITY

    addUnitPriority('minute', 14);

    // PARSING

    addRegexToken('m', match1to2);
    addRegexToken('mm', match1to2, match2);
    addParseToken(['m', 'mm'], MINUTE);

    // MOMENTS

    var getSetMinute = makeGetSet('Minutes', false);

    // FORMATTING

    addFormatToken('s', ['ss', 2], 0, 'second');

    // ALIASES

    addUnitAlias('second', 's');

    // PRIORITY

    addUnitPriority('second', 15);

    // PARSING

    addRegexToken('s', match1to2);
    addRegexToken('ss', match1to2, match2);
    addParseToken(['s', 'ss'], SECOND);

    // MOMENTS

    var getSetSecond = makeGetSet('Seconds', false);

    // FORMATTING

    addFormatToken('S', 0, 0, function () {
        return ~~(this.millisecond() / 100);
    });

    addFormatToken(0, ['SS', 2], 0, function () {
        return ~~(this.millisecond() / 10);
    });

    addFormatToken(0, ['SSS', 3], 0, 'millisecond');
    addFormatToken(0, ['SSSS', 4], 0, function () {
        return this.millisecond() * 10;
    });
    addFormatToken(0, ['SSSSS', 5], 0, function () {
        return this.millisecond() * 100;
    });
    addFormatToken(0, ['SSSSSS', 6], 0, function () {
        return this.millisecond() * 1000;
    });
    addFormatToken(0, ['SSSSSSS', 7], 0, function () {
        return this.millisecond() * 10000;
    });
    addFormatToken(0, ['SSSSSSSS', 8], 0, function () {
        return this.millisecond() * 100000;
    });
    addFormatToken(0, ['SSSSSSSSS', 9], 0, function () {
        return this.millisecond() * 1000000;
    });

    // ALIASES

    addUnitAlias('millisecond', 'ms');

    // PRIORITY

    addUnitPriority('millisecond', 16);

    // PARSING

    addRegexToken('S', match1to3, match1);
    addRegexToken('SS', match1to3, match2);
    addRegexToken('SSS', match1to3, match3);

    var token, getSetMillisecond;
    for (token = 'SSSS'; token.length <= 9; token += 'S') {
        addRegexToken(token, matchUnsigned);
    }

    function parseMs(input, array) {
        array[MILLISECOND] = toInt(('0.' + input) * 1000);
    }

    for (token = 'S'; token.length <= 9; token += 'S') {
        addParseToken(token, parseMs);
    }

    getSetMillisecond = makeGetSet('Milliseconds', false);

    // FORMATTING

    addFormatToken('z', 0, 0, 'zoneAbbr');
    addFormatToken('zz', 0, 0, 'zoneName');

    // MOMENTS

    function getZoneAbbr() {
        return this._isUTC ? 'UTC' : '';
    }

    function getZoneName() {
        return this._isUTC ? 'Coordinated Universal Time' : '';
    }

    var proto = Moment.prototype;

    proto.add = add;
    proto.calendar = calendar$1;
    proto.clone = clone;
    proto.diff = diff;
    proto.endOf = endOf;
    proto.format = format;
    proto.from = from;
    proto.fromNow = fromNow;
    proto.to = to;
    proto.toNow = toNow;
    proto.get = stringGet;
    proto.invalidAt = invalidAt;
    proto.isAfter = isAfter;
    proto.isBefore = isBefore;
    proto.isBetween = isBetween;
    proto.isSame = isSame;
    proto.isSameOrAfter = isSameOrAfter;
    proto.isSameOrBefore = isSameOrBefore;
    proto.isValid = isValid$2;
    proto.lang = lang;
    proto.locale = locale;
    proto.localeData = localeData;
    proto.max = prototypeMax;
    proto.min = prototypeMin;
    proto.parsingFlags = parsingFlags;
    proto.set = stringSet;
    proto.startOf = startOf;
    proto.subtract = subtract;
    proto.toArray = toArray;
    proto.toObject = toObject;
    proto.toDate = toDate;
    proto.toISOString = toISOString;
    proto.inspect = inspect;
    if (typeof Symbol !== 'undefined' && Symbol.for != null) {
        proto[Symbol.for('nodejs.util.inspect.custom')] = function () {
            return 'Moment<' + this.format() + '>';
        };
    }
    proto.toJSON = toJSON;
    proto.toString = toString;
    proto.unix = unix;
    proto.valueOf = valueOf;
    proto.creationData = creationData;
    proto.eraName = getEraName;
    proto.eraNarrow = getEraNarrow;
    proto.eraAbbr = getEraAbbr;
    proto.eraYear = getEraYear;
    proto.year = getSetYear;
    proto.isLeapYear = getIsLeapYear;
    proto.weekYear = getSetWeekYear;
    proto.isoWeekYear = getSetISOWeekYear;
    proto.quarter = proto.quarters = getSetQuarter;
    proto.month = getSetMonth;
    proto.daysInMonth = getDaysInMonth;
    proto.week = proto.weeks = getSetWeek;
    proto.isoWeek = proto.isoWeeks = getSetISOWeek;
    proto.weeksInYear = getWeeksInYear;
    proto.weeksInWeekYear = getWeeksInWeekYear;
    proto.isoWeeksInYear = getISOWeeksInYear;
    proto.isoWeeksInISOWeekYear = getISOWeeksInISOWeekYear;
    proto.date = getSetDayOfMonth;
    proto.day = proto.days = getSetDayOfWeek;
    proto.weekday = getSetLocaleDayOfWeek;
    proto.isoWeekday = getSetISODayOfWeek;
    proto.dayOfYear = getSetDayOfYear;
    proto.hour = proto.hours = getSetHour;
    proto.minute = proto.minutes = getSetMinute;
    proto.second = proto.seconds = getSetSecond;
    proto.millisecond = proto.milliseconds = getSetMillisecond;
    proto.utcOffset = getSetOffset;
    proto.utc = setOffsetToUTC;
    proto.local = setOffsetToLocal;
    proto.parseZone = setOffsetToParsedOffset;
    proto.hasAlignedHourOffset = hasAlignedHourOffset;
    proto.isDST = isDaylightSavingTime;
    proto.isLocal = isLocal;
    proto.isUtcOffset = isUtcOffset;
    proto.isUtc = isUtc;
    proto.isUTC = isUtc;
    proto.zoneAbbr = getZoneAbbr;
    proto.zoneName = getZoneName;
    proto.dates = deprecate(
        'dates accessor is deprecated. Use date instead.',
        getSetDayOfMonth
    );
    proto.months = deprecate(
        'months accessor is deprecated. Use month instead',
        getSetMonth
    );
    proto.years = deprecate(
        'years accessor is deprecated. Use year instead',
        getSetYear
    );
    proto.zone = deprecate(
        'moment().zone is deprecated, use moment().utcOffset instead. http://momentjs.com/guides/#/warnings/zone/',
        getSetZone
    );
    proto.isDSTShifted = deprecate(
        'isDSTShifted is deprecated. See http://momentjs.com/guides/#/warnings/dst-shifted/ for more information',
        isDaylightSavingTimeShifted
    );

    function createUnix(input) {
        return createLocal(input * 1000);
    }

    function createInZone() {
        return createLocal.apply(null, arguments).parseZone();
    }

    function preParsePostFormat(string) {
        return string;
    }

    var proto$1 = Locale.prototype;

    proto$1.calendar = calendar;
    proto$1.longDateFormat = longDateFormat;
    proto$1.invalidDate = invalidDate;
    proto$1.ordinal = ordinal;
    proto$1.preparse = preParsePostFormat;
    proto$1.postformat = preParsePostFormat;
    proto$1.relativeTime = relativeTime;
    proto$1.pastFuture = pastFuture;
    proto$1.set = set;
    proto$1.eras = localeEras;
    proto$1.erasParse = localeErasParse;
    proto$1.erasConvertYear = localeErasConvertYear;
    proto$1.erasAbbrRegex = erasAbbrRegex;
    proto$1.erasNameRegex = erasNameRegex;
    proto$1.erasNarrowRegex = erasNarrowRegex;

    proto$1.months = localeMonths;
    proto$1.monthsShort = localeMonthsShort;
    proto$1.monthsParse = localeMonthsParse;
    proto$1.monthsRegex = monthsRegex;
    proto$1.monthsShortRegex = monthsShortRegex;
    proto$1.week = localeWeek;
    proto$1.firstDayOfYear = localeFirstDayOfYear;
    proto$1.firstDayOfWeek = localeFirstDayOfWeek;

    proto$1.weekdays = localeWeekdays;
    proto$1.weekdaysMin = localeWeekdaysMin;
    proto$1.weekdaysShort = localeWeekdaysShort;
    proto$1.weekdaysParse = localeWeekdaysParse;

    proto$1.weekdaysRegex = weekdaysRegex;
    proto$1.weekdaysShortRegex = weekdaysShortRegex;
    proto$1.weekdaysMinRegex = weekdaysMinRegex;

    proto$1.isPM = localeIsPM;
    proto$1.meridiem = localeMeridiem;

    function get$1(format, index, field, setter) {
        var locale = getLocale(),
            utc = createUTC().set(setter, index);
        return locale[field](utc, format);
    }

    function listMonthsImpl(format, index, field) {
        if (isNumber(format)) {
            index = format;
            format = undefined;
        }

        format = format || '';

        if (index != null) {
            return get$1(format, index, field, 'month');
        }

        var i,
            out = [];
        for (i = 0; i < 12; i++) {
            out[i] = get$1(format, i, field, 'month');
        }
        return out;
    }

    // ()
    // (5)
    // (fmt, 5)
    // (fmt)
    // (true)
    // (true, 5)
    // (true, fmt, 5)
    // (true, fmt)
    function listWeekdaysImpl(localeSorted, format, index, field) {
        if (typeof localeSorted === 'boolean') {
            if (isNumber(format)) {
                index = format;
                format = undefined;
            }

            format = format || '';
        } else {
            format = localeSorted;
            index = format;
            localeSorted = false;

            if (isNumber(format)) {
                index = format;
                format = undefined;
            }

            format = format || '';
        }

        var locale = getLocale(),
            shift = localeSorted ? locale._week.dow : 0,
            i,
            out = [];

        if (index != null) {
            return get$1(format, (index + shift) % 7, field, 'day');
        }

        for (i = 0; i < 7; i++) {
            out[i] = get$1(format, (i + shift) % 7, field, 'day');
        }
        return out;
    }

    function listMonths(format, index) {
        return listMonthsImpl(format, index, 'months');
    }

    function listMonthsShort(format, index) {
        return listMonthsImpl(format, index, 'monthsShort');
    }

    function listWeekdays(localeSorted, format, index) {
        return listWeekdaysImpl(localeSorted, format, index, 'weekdays');
    }

    function listWeekdaysShort(localeSorted, format, index) {
        return listWeekdaysImpl(localeSorted, format, index, 'weekdaysShort');
    }

    function listWeekdaysMin(localeSorted, format, index) {
        return listWeekdaysImpl(localeSorted, format, index, 'weekdaysMin');
    }

    getSetGlobalLocale('en', {
        eras: [
            {
                since: '0001-01-01',
                until: +Infinity,
                offset: 1,
                name: 'Anno Domini',
                narrow: 'AD',
                abbr: 'AD',
            },
            {
                since: '0000-12-31',
                until: -Infinity,
                offset: 1,
                name: 'Before Christ',
                narrow: 'BC',
                abbr: 'BC',
            },
        ],
        dayOfMonthOrdinalParse: /\d{1,2}(th|st|nd|rd)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    toInt((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
    });

    // Side effect imports

    hooks.lang = deprecate(
        'moment.lang is deprecated. Use moment.locale instead.',
        getSetGlobalLocale
    );
    hooks.langData = deprecate(
        'moment.langData is deprecated. Use moment.localeData instead.',
        getLocale
    );

    var mathAbs = Math.abs;

    function abs() {
        var data = this._data;

        this._milliseconds = mathAbs(this._milliseconds);
        this._days = mathAbs(this._days);
        this._months = mathAbs(this._months);

        data.milliseconds = mathAbs(data.milliseconds);
        data.seconds = mathAbs(data.seconds);
        data.minutes = mathAbs(data.minutes);
        data.hours = mathAbs(data.hours);
        data.months = mathAbs(data.months);
        data.years = mathAbs(data.years);

        return this;
    }

    function addSubtract$1(duration, input, value, direction) {
        var other = createDuration(input, value);

        duration._milliseconds += direction * other._milliseconds;
        duration._days += direction * other._days;
        duration._months += direction * other._months;

        return duration._bubble();
    }

    // supports only 2.0-style add(1, 's') or add(duration)
    function add$1(input, value) {
        return addSubtract$1(this, input, value, 1);
    }

    // supports only 2.0-style subtract(1, 's') or subtract(duration)
    function subtract$1(input, value) {
        return addSubtract$1(this, input, value, -1);
    }

    function absCeil(number) {
        if (number < 0) {
            return Math.floor(number);
        } else {
            return Math.ceil(number);
        }
    }

    function bubble() {
        var milliseconds = this._milliseconds,
            days = this._days,
            months = this._months,
            data = this._data,
            seconds,
            minutes,
            hours,
            years,
            monthsFromDays;

        // if we have a mix of positive and negative values, bubble down first
        // check: https://github.com/moment/moment/issues/2166
        if (
            !(
                (milliseconds >= 0 && days >= 0 && months >= 0) ||
                (milliseconds <= 0 && days <= 0 && months <= 0)
            )
        ) {
            milliseconds += absCeil(monthsToDays(months) + days) * 864e5;
            days = 0;
            months = 0;
        }

        // The following code bubbles up values, see the tests for
        // examples of what that means.
        data.milliseconds = milliseconds % 1000;

        seconds = absFloor(milliseconds / 1000);
        data.seconds = seconds % 60;

        minutes = absFloor(seconds / 60);
        data.minutes = minutes % 60;

        hours = absFloor(minutes / 60);
        data.hours = hours % 24;

        days += absFloor(hours / 24);

        // convert days to months
        monthsFromDays = absFloor(daysToMonths(days));
        months += monthsFromDays;
        days -= absCeil(monthsToDays(monthsFromDays));

        // 12 months -> 1 year
        years = absFloor(months / 12);
        months %= 12;

        data.days = days;
        data.months = months;
        data.years = years;

        return this;
    }

    function daysToMonths(days) {
        // 400 years have 146097 days (taking into account leap year rules)
        // 400 years have 12 months === 4800
        return (days * 4800) / 146097;
    }

    function monthsToDays(months) {
        // the reverse of daysToMonths
        return (months * 146097) / 4800;
    }

    function as(units) {
        if (!this.isValid()) {
            return NaN;
        }
        var days,
            months,
            milliseconds = this._milliseconds;

        units = normalizeUnits(units);

        if (units === 'month' || units === 'quarter' || units === 'year') {
            days = this._days + milliseconds / 864e5;
            months = this._months + daysToMonths(days);
            switch (units) {
                case 'month':
                    return months;
                case 'quarter':
                    return months / 3;
                case 'year':
                    return months / 12;
            }
        } else {
            // handle milliseconds separately because of floating point math errors (issue #1867)
            days = this._days + Math.round(monthsToDays(this._months));
            switch (units) {
                case 'week':
                    return days / 7 + milliseconds / 6048e5;
                case 'day':
                    return days + milliseconds / 864e5;
                case 'hour':
                    return days * 24 + milliseconds / 36e5;
                case 'minute':
                    return days * 1440 + milliseconds / 6e4;
                case 'second':
                    return days * 86400 + milliseconds / 1000;
                // Math.floor prevents floating point math errors here
                case 'millisecond':
                    return Math.floor(days * 864e5) + milliseconds;
                default:
                    throw new Error('Unknown unit ' + units);
            }
        }
    }

    // TODO: Use this.as('ms')?
    function valueOf$1() {
        if (!this.isValid()) {
            return NaN;
        }
        return (
            this._milliseconds +
            this._days * 864e5 +
            (this._months % 12) * 2592e6 +
            toInt(this._months / 12) * 31536e6
        );
    }

    function makeAs(alias) {
        return function () {
            return this.as(alias);
        };
    }

    var asMilliseconds = makeAs('ms'),
        asSeconds = makeAs('s'),
        asMinutes = makeAs('m'),
        asHours = makeAs('h'),
        asDays = makeAs('d'),
        asWeeks = makeAs('w'),
        asMonths = makeAs('M'),
        asQuarters = makeAs('Q'),
        asYears = makeAs('y');

    function clone$1() {
        return createDuration(this);
    }

    function get$2(units) {
        units = normalizeUnits(units);
        return this.isValid() ? this[units + 's']() : NaN;
    }

    function makeGetter(name) {
        return function () {
            return this.isValid() ? this._data[name] : NaN;
        };
    }

    var milliseconds = makeGetter('milliseconds'),
        seconds = makeGetter('seconds'),
        minutes = makeGetter('minutes'),
        hours = makeGetter('hours'),
        days = makeGetter('days'),
        months = makeGetter('months'),
        years = makeGetter('years');

    function weeks() {
        return absFloor(this.days() / 7);
    }

    var round = Math.round,
        thresholds = {
            ss: 44, // a few seconds to seconds
            s: 45, // seconds to minute
            m: 45, // minutes to hour
            h: 22, // hours to day
            d: 26, // days to month/week
            w: null, // weeks to month
            M: 11, // months to year
        };

    // helper function for moment.fn.from, moment.fn.fromNow, and moment.duration.fn.humanize
    function substituteTimeAgo(string, number, withoutSuffix, isFuture, locale) {
        return locale.relativeTime(number || 1, !!withoutSuffix, string, isFuture);
    }

    function relativeTime$1(posNegDuration, withoutSuffix, thresholds, locale) {
        var duration = createDuration(posNegDuration).abs(),
            seconds = round(duration.as('s')),
            minutes = round(duration.as('m')),
            hours = round(duration.as('h')),
            days = round(duration.as('d')),
            months = round(duration.as('M')),
            weeks = round(duration.as('w')),
            years = round(duration.as('y')),
            a =
                (seconds <= thresholds.ss && ['s', seconds]) ||
                (seconds < thresholds.s && ['ss', seconds]) ||
                (minutes <= 1 && ['m']) ||
                (minutes < thresholds.m && ['mm', minutes]) ||
                (hours <= 1 && ['h']) ||
                (hours < thresholds.h && ['hh', hours]) ||
                (days <= 1 && ['d']) ||
                (days < thresholds.d && ['dd', days]);

        if (thresholds.w != null) {
            a =
                a ||
                (weeks <= 1 && ['w']) ||
                (weeks < thresholds.w && ['ww', weeks]);
        }
        a = a ||
            (months <= 1 && ['M']) ||
            (months < thresholds.M && ['MM', months]) ||
            (years <= 1 && ['y']) || ['yy', years];

        a[2] = withoutSuffix;
        a[3] = +posNegDuration > 0;
        a[4] = locale;
        return substituteTimeAgo.apply(null, a);
    }

    // This function allows you to set the rounding function for relative time strings
    function getSetRelativeTimeRounding(roundingFunction) {
        if (roundingFunction === undefined) {
            return round;
        }
        if (typeof roundingFunction === 'function') {
            round = roundingFunction;
            return true;
        }
        return false;
    }

    // This function allows you to set a threshold for relative time strings
    function getSetRelativeTimeThreshold(threshold, limit) {
        if (thresholds[threshold] === undefined) {
            return false;
        }
        if (limit === undefined) {
            return thresholds[threshold];
        }
        thresholds[threshold] = limit;
        if (threshold === 's') {
            thresholds.ss = limit - 1;
        }
        return true;
    }

    function humanize(argWithSuffix, argThresholds) {
        if (!this.isValid()) {
            return this.localeData().invalidDate();
        }

        var withSuffix = false,
            th = thresholds,
            locale,
            output;

        if (typeof argWithSuffix === 'object') {
            argThresholds = argWithSuffix;
            argWithSuffix = false;
        }
        if (typeof argWithSuffix === 'boolean') {
            withSuffix = argWithSuffix;
        }
        if (typeof argThresholds === 'object') {
            th = Object.assign({}, thresholds, argThresholds);
            if (argThresholds.s != null && argThresholds.ss == null) {
                th.ss = argThresholds.s - 1;
            }
        }

        locale = this.localeData();
        output = relativeTime$1(this, !withSuffix, th, locale);

        if (withSuffix) {
            output = locale.pastFuture(+this, output);
        }

        return locale.postformat(output);
    }

    var abs$1 = Math.abs;

    function sign(x) {
        return (x > 0) - (x < 0) || +x;
    }

    function toISOString$1() {
        // for ISO strings we do not use the normal bubbling rules:
        //  * milliseconds bubble up until they become hours
        //  * days do not bubble at all
        //  * months bubble up until they become years
        // This is because there is no context-free conversion between hours and days
        // (think of clock changes)
        // and also not between days and months (28-31 days per month)
        if (!this.isValid()) {
            return this.localeData().invalidDate();
        }

        var seconds = abs$1(this._milliseconds) / 1000,
            days = abs$1(this._days),
            months = abs$1(this._months),
            minutes,
            hours,
            years,
            s,
            total = this.asSeconds(),
            totalSign,
            ymSign,
            daysSign,
            hmsSign;

        if (!total) {
            // this is the same as C#'s (Noda) and python (isodate)...
            // but not other JS (goog.date)
            return 'P0D';
        }

        // 3600 seconds -> 60 minutes -> 1 hour
        minutes = absFloor(seconds / 60);
        hours = absFloor(minutes / 60);
        seconds %= 60;
        minutes %= 60;

        // 12 months -> 1 year
        years = absFloor(months / 12);
        months %= 12;

        // inspired by https://github.com/dordille/moment-isoduration/blob/master/moment.isoduration.js
        s = seconds ? seconds.toFixed(3).replace(/\.?0+$/, '') : '';

        totalSign = total < 0 ? '-' : '';
        ymSign = sign(this._months) !== sign(total) ? '-' : '';
        daysSign = sign(this._days) !== sign(total) ? '-' : '';
        hmsSign = sign(this._milliseconds) !== sign(total) ? '-' : '';

        return (
            totalSign +
            'P' +
            (years ? ymSign + years + 'Y' : '') +
            (months ? ymSign + months + 'M' : '') +
            (days ? daysSign + days + 'D' : '') +
            (hours || minutes || seconds ? 'T' : '') +
            (hours ? hmsSign + hours + 'H' : '') +
            (minutes ? hmsSign + minutes + 'M' : '') +
            (seconds ? hmsSign + s + 'S' : '')
        );
    }

    var proto$2 = Duration.prototype;

    proto$2.isValid = isValid$1;
    proto$2.abs = abs;
    proto$2.add = add$1;
    proto$2.subtract = subtract$1;
    proto$2.as = as;
    proto$2.asMilliseconds = asMilliseconds;
    proto$2.asSeconds = asSeconds;
    proto$2.asMinutes = asMinutes;
    proto$2.asHours = asHours;
    proto$2.asDays = asDays;
    proto$2.asWeeks = asWeeks;
    proto$2.asMonths = asMonths;
    proto$2.asQuarters = asQuarters;
    proto$2.asYears = asYears;
    proto$2.valueOf = valueOf$1;
    proto$2._bubble = bubble;
    proto$2.clone = clone$1;
    proto$2.get = get$2;
    proto$2.milliseconds = milliseconds;
    proto$2.seconds = seconds;
    proto$2.minutes = minutes;
    proto$2.hours = hours;
    proto$2.days = days;
    proto$2.weeks = weeks;
    proto$2.months = months;
    proto$2.years = years;
    proto$2.humanize = humanize;
    proto$2.toISOString = toISOString$1;
    proto$2.toString = toISOString$1;
    proto$2.toJSON = toISOString$1;
    proto$2.locale = locale;
    proto$2.localeData = localeData;

    proto$2.toIsoString = deprecate(
        'toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)',
        toISOString$1
    );
    proto$2.lang = lang;

    // FORMATTING

    addFormatToken('X', 0, 0, 'unix');
    addFormatToken('x', 0, 0, 'valueOf');

    // PARSING

    addRegexToken('x', matchSigned);
    addRegexToken('X', matchTimestamp);
    addParseToken('X', function (input, array, config) {
        config._d = new Date(parseFloat(input) * 1000);
    });
    addParseToken('x', function (input, array, config) {
        config._d = new Date(toInt(input));
    });

    //! moment.js

    hooks.version = '2.29.4';

    setHookCallback(createLocal);

    hooks.fn = proto;
    hooks.min = min;
    hooks.max = max;
    hooks.now = now;
    hooks.utc = createUTC;
    hooks.unix = createUnix;
    hooks.months = listMonths;
    hooks.isDate = isDate;
    hooks.locale = getSetGlobalLocale;
    hooks.invalid = createInvalid;
    hooks.duration = createDuration;
    hooks.isMoment = isMoment;
    hooks.weekdays = listWeekdays;
    hooks.parseZone = createInZone;
    hooks.localeData = getLocale;
    hooks.isDuration = isDuration;
    hooks.monthsShort = listMonthsShort;
    hooks.weekdaysMin = listWeekdaysMin;
    hooks.defineLocale = defineLocale;
    hooks.updateLocale = updateLocale;
    hooks.locales = listLocales;
    hooks.weekdaysShort = listWeekdaysShort;
    hooks.normalizeUnits = normalizeUnits;
    hooks.relativeTimeRounding = getSetRelativeTimeRounding;
    hooks.relativeTimeThreshold = getSetRelativeTimeThreshold;
    hooks.calendarFormat = getCalendarFormat;
    hooks.prototype = proto;

    // currently HTML5 input type only supports 24-hour formats
    hooks.HTML5_FMT = {
        DATETIME_LOCAL: 'YYYY-MM-DDTHH:mm', // <input type="datetime-local" />
        DATETIME_LOCAL_SECONDS: 'YYYY-MM-DDTHH:mm:ss', // <input type="datetime-local" step="1" />
        DATETIME_LOCAL_MS: 'YYYY-MM-DDTHH:mm:ss.SSS', // <input type="datetime-local" step="0.001" />
        DATE: 'YYYY-MM-DD', // <input type="date" />
        TIME: 'HH:mm', // <input type="time" />
        TIME_SECONDS: 'HH:mm:ss', // <input type="time" step="1" />
        TIME_MS: 'HH:mm:ss.SSS', // <input type="time" step="0.001" />
        WEEK: 'GGGG-[W]WW', // <input type="week" />
        MONTH: 'YYYY-MM', // <input type="month" />
    };

    //! moment.js locale configuration

    hooks.defineLocale('af', {
        months: 'Januarie_Februarie_Maart_April_Mei_Junie_Julie_Augustus_September_Oktober_November_Desember'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mrt_Apr_Mei_Jun_Jul_Aug_Sep_Okt_Nov_Des'.split('_'),
        weekdays: 'Sondag_Maandag_Dinsdag_Woensdag_Donderdag_Vrydag_Saterdag'.split(
            '_'
        ),
        weekdaysShort: 'Son_Maa_Din_Woe_Don_Vry_Sat'.split('_'),
        weekdaysMin: 'So_Ma_Di_Wo_Do_Vr_Sa'.split('_'),
        meridiemParse: /vm|nm/i,
        isPM: function (input) {
            return /^nm$/i.test(input);
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 12) {
                return isLower ? 'vm' : 'VM';
            } else {
                return isLower ? 'nm' : 'NM';
            }
        },
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Vandag om] LT',
            nextDay: '[Môre om] LT',
            nextWeek: 'dddd [om] LT',
            lastDay: '[Gister om] LT',
            lastWeek: '[Laas] dddd [om] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'oor %s',
            past: '%s gelede',
            s: "'n paar sekondes",
            ss: '%d sekondes',
            m: "'n minuut",
            mm: '%d minute',
            h: "'n uur",
            hh: '%d ure',
            d: "'n dag",
            dd: '%d dae',
            M: "'n maand",
            MM: '%d maande',
            y: "'n jaar",
            yy: '%d jaar',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(ste|de)/,
        ordinal: function (number) {
            return (
                number +
                (number === 1 || number === 8 || number >= 20 ? 'ste' : 'de')
            ); // Thanks to Joris Röling : https://github.com/jjupiter
        },
        week: {
            dow: 1, // Maandag is die eerste dag van die week.
            doy: 4, // Die week wat die 4de Januarie bevat is die eerste week van die jaar.
        },
    });

    //! moment.js locale configuration

    var pluralForm = function (n) {
            return n === 0
                ? 0
                : n === 1
                ? 1
                : n === 2
                ? 2
                : n % 100 >= 3 && n % 100 <= 10
                ? 3
                : n % 100 >= 11
                ? 4
                : 5;
        },
        plurals = {
            s: [
                'أقل من ثانية',
                'ثانية واحدة',
                ['ثانيتان', 'ثانيتين'],
                '%d ثوان',
                '%d ثانية',
                '%d ثانية',
            ],
            m: [
                'أقل من دقيقة',
                'دقيقة واحدة',
                ['دقيقتان', 'دقيقتين'],
                '%d دقائق',
                '%d دقيقة',
                '%d دقيقة',
            ],
            h: [
                'أقل من ساعة',
                'ساعة واحدة',
                ['ساعتان', 'ساعتين'],
                '%d ساعات',
                '%d ساعة',
                '%d ساعة',
            ],
            d: [
                'أقل من يوم',
                'يوم واحد',
                ['يومان', 'يومين'],
                '%d أيام',
                '%d يومًا',
                '%d يوم',
            ],
            M: [
                'أقل من شهر',
                'شهر واحد',
                ['شهران', 'شهرين'],
                '%d أشهر',
                '%d شهرا',
                '%d شهر',
            ],
            y: [
                'أقل من عام',
                'عام واحد',
                ['عامان', 'عامين'],
                '%d أعوام',
                '%d عامًا',
                '%d عام',
            ],
        },
        pluralize = function (u) {
            return function (number, withoutSuffix, string, isFuture) {
                var f = pluralForm(number),
                    str = plurals[u][pluralForm(number)];
                if (f === 2) {
                    str = str[withoutSuffix ? 0 : 1];
                }
                return str.replace(/%d/i, number);
            };
        },
        months$1 = [
            'جانفي',
            'فيفري',
            'مارس',
            'أفريل',
            'ماي',
            'جوان',
            'جويلية',
            'أوت',
            'سبتمبر',
            'أكتوبر',
            'نوفمبر',
            'ديسمبر',
        ];

    hooks.defineLocale('ar-dz', {
        months: months$1,
        monthsShort: months$1,
        weekdays: 'الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت'.split('_'),
        weekdaysShort: 'أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت'.split('_'),
        weekdaysMin: 'ح_ن_ث_ر_خ_ج_س'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'D/\u200FM/\u200FYYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        meridiemParse: /ص|م/,
        isPM: function (input) {
            return 'م' === input;
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ص';
            } else {
                return 'م';
            }
        },
        calendar: {
            sameDay: '[اليوم عند الساعة] LT',
            nextDay: '[غدًا عند الساعة] LT',
            nextWeek: 'dddd [عند الساعة] LT',
            lastDay: '[أمس عند الساعة] LT',
            lastWeek: 'dddd [عند الساعة] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'بعد %s',
            past: 'منذ %s',
            s: pluralize('s'),
            ss: pluralize('s'),
            m: pluralize('m'),
            mm: pluralize('m'),
            h: pluralize('h'),
            hh: pluralize('h'),
            d: pluralize('d'),
            dd: pluralize('d'),
            M: pluralize('M'),
            MM: pluralize('M'),
            y: pluralize('y'),
            yy: pluralize('y'),
        },
        postformat: function (string) {
            return string.replace(/,/g, '،');
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ar-kw', {
        months: 'يناير_فبراير_مارس_أبريل_ماي_يونيو_يوليوز_غشت_شتنبر_أكتوبر_نونبر_دجنبر'.split(
            '_'
        ),
        monthsShort:
            'يناير_فبراير_مارس_أبريل_ماي_يونيو_يوليوز_غشت_شتنبر_أكتوبر_نونبر_دجنبر'.split(
                '_'
            ),
        weekdays: 'الأحد_الإتنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت'.split('_'),
        weekdaysShort: 'احد_اتنين_ثلاثاء_اربعاء_خميس_جمعة_سبت'.split('_'),
        weekdaysMin: 'ح_ن_ث_ر_خ_ج_س'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[اليوم على الساعة] LT',
            nextDay: '[غدا على الساعة] LT',
            nextWeek: 'dddd [على الساعة] LT',
            lastDay: '[أمس على الساعة] LT',
            lastWeek: 'dddd [على الساعة] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'في %s',
            past: 'منذ %s',
            s: 'ثوان',
            ss: '%d ثانية',
            m: 'دقيقة',
            mm: '%d دقائق',
            h: 'ساعة',
            hh: '%d ساعات',
            d: 'يوم',
            dd: '%d أيام',
            M: 'شهر',
            MM: '%d أشهر',
            y: 'سنة',
            yy: '%d سنوات',
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap = {
            1: '1',
            2: '2',
            3: '3',
            4: '4',
            5: '5',
            6: '6',
            7: '7',
            8: '8',
            9: '9',
            0: '0',
        },
        pluralForm$1 = function (n) {
            return n === 0
                ? 0
                : n === 1
                ? 1
                : n === 2
                ? 2
                : n % 100 >= 3 && n % 100 <= 10
                ? 3
                : n % 100 >= 11
                ? 4
                : 5;
        },
        plurals$1 = {
            s: [
                'أقل من ثانية',
                'ثانية واحدة',
                ['ثانيتان', 'ثانيتين'],
                '%d ثوان',
                '%d ثانية',
                '%d ثانية',
            ],
            m: [
                'أقل من دقيقة',
                'دقيقة واحدة',
                ['دقيقتان', 'دقيقتين'],
                '%d دقائق',
                '%d دقيقة',
                '%d دقيقة',
            ],
            h: [
                'أقل من ساعة',
                'ساعة واحدة',
                ['ساعتان', 'ساعتين'],
                '%d ساعات',
                '%d ساعة',
                '%d ساعة',
            ],
            d: [
                'أقل من يوم',
                'يوم واحد',
                ['يومان', 'يومين'],
                '%d أيام',
                '%d يومًا',
                '%d يوم',
            ],
            M: [
                'أقل من شهر',
                'شهر واحد',
                ['شهران', 'شهرين'],
                '%d أشهر',
                '%d شهرا',
                '%d شهر',
            ],
            y: [
                'أقل من عام',
                'عام واحد',
                ['عامان', 'عامين'],
                '%d أعوام',
                '%d عامًا',
                '%d عام',
            ],
        },
        pluralize$1 = function (u) {
            return function (number, withoutSuffix, string, isFuture) {
                var f = pluralForm$1(number),
                    str = plurals$1[u][pluralForm$1(number)];
                if (f === 2) {
                    str = str[withoutSuffix ? 0 : 1];
                }
                return str.replace(/%d/i, number);
            };
        },
        months$2 = [
            'يناير',
            'فبراير',
            'مارس',
            'أبريل',
            'مايو',
            'يونيو',
            'يوليو',
            'أغسطس',
            'سبتمبر',
            'أكتوبر',
            'نوفمبر',
            'ديسمبر',
        ];

    hooks.defineLocale('ar-ly', {
        months: months$2,
        monthsShort: months$2,
        weekdays: 'الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت'.split('_'),
        weekdaysShort: 'أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت'.split('_'),
        weekdaysMin: 'ح_ن_ث_ر_خ_ج_س'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'D/\u200FM/\u200FYYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        meridiemParse: /ص|م/,
        isPM: function (input) {
            return 'م' === input;
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ص';
            } else {
                return 'م';
            }
        },
        calendar: {
            sameDay: '[اليوم عند الساعة] LT',
            nextDay: '[غدًا عند الساعة] LT',
            nextWeek: 'dddd [عند الساعة] LT',
            lastDay: '[أمس عند الساعة] LT',
            lastWeek: 'dddd [عند الساعة] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'بعد %s',
            past: 'منذ %s',
            s: pluralize$1('s'),
            ss: pluralize$1('s'),
            m: pluralize$1('m'),
            mm: pluralize$1('m'),
            h: pluralize$1('h'),
            hh: pluralize$1('h'),
            d: pluralize$1('d'),
            dd: pluralize$1('d'),
            M: pluralize$1('M'),
            MM: pluralize$1('M'),
            y: pluralize$1('y'),
            yy: pluralize$1('y'),
        },
        preparse: function (string) {
            return string.replace(/،/g, ',');
        },
        postformat: function (string) {
            return string
                .replace(/\d/g, function (match) {
                    return symbolMap[match];
                })
                .replace(/,/g, '،');
        },
        week: {
            dow: 6, // Saturday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ar-ma', {
        months: 'يناير_فبراير_مارس_أبريل_ماي_يونيو_يوليوز_غشت_شتنبر_أكتوبر_نونبر_دجنبر'.split(
            '_'
        ),
        monthsShort:
            'يناير_فبراير_مارس_أبريل_ماي_يونيو_يوليوز_غشت_شتنبر_أكتوبر_نونبر_دجنبر'.split(
                '_'
            ),
        weekdays: 'الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت'.split('_'),
        weekdaysShort: 'احد_اثنين_ثلاثاء_اربعاء_خميس_جمعة_سبت'.split('_'),
        weekdaysMin: 'ح_ن_ث_ر_خ_ج_س'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[اليوم على الساعة] LT',
            nextDay: '[غدا على الساعة] LT',
            nextWeek: 'dddd [على الساعة] LT',
            lastDay: '[أمس على الساعة] LT',
            lastWeek: 'dddd [على الساعة] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'في %s',
            past: 'منذ %s',
            s: 'ثوان',
            ss: '%d ثانية',
            m: 'دقيقة',
            mm: '%d دقائق',
            h: 'ساعة',
            hh: '%d ساعات',
            d: 'يوم',
            dd: '%d أيام',
            M: 'شهر',
            MM: '%d أشهر',
            y: 'سنة',
            yy: '%d سنوات',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$1 = {
            1: '١',
            2: '٢',
            3: '٣',
            4: '٤',
            5: '٥',
            6: '٦',
            7: '٧',
            8: '٨',
            9: '٩',
            0: '٠',
        },
        numberMap = {
            '١': '1',
            '٢': '2',
            '٣': '3',
            '٤': '4',
            '٥': '5',
            '٦': '6',
            '٧': '7',
            '٨': '8',
            '٩': '9',
            '٠': '0',
        };

    hooks.defineLocale('ar-sa', {
        months: 'يناير_فبراير_مارس_أبريل_مايو_يونيو_يوليو_أغسطس_سبتمبر_أكتوبر_نوفمبر_ديسمبر'.split(
            '_'
        ),
        monthsShort:
            'يناير_فبراير_مارس_أبريل_مايو_يونيو_يوليو_أغسطس_سبتمبر_أكتوبر_نوفمبر_ديسمبر'.split(
                '_'
            ),
        weekdays: 'الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت'.split('_'),
        weekdaysShort: 'أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت'.split('_'),
        weekdaysMin: 'ح_ن_ث_ر_خ_ج_س'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        meridiemParse: /ص|م/,
        isPM: function (input) {
            return 'م' === input;
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ص';
            } else {
                return 'م';
            }
        },
        calendar: {
            sameDay: '[اليوم على الساعة] LT',
            nextDay: '[غدا على الساعة] LT',
            nextWeek: 'dddd [على الساعة] LT',
            lastDay: '[أمس على الساعة] LT',
            lastWeek: 'dddd [على الساعة] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'في %s',
            past: 'منذ %s',
            s: 'ثوان',
            ss: '%d ثانية',
            m: 'دقيقة',
            mm: '%d دقائق',
            h: 'ساعة',
            hh: '%d ساعات',
            d: 'يوم',
            dd: '%d أيام',
            M: 'شهر',
            MM: '%d أشهر',
            y: 'سنة',
            yy: '%d سنوات',
        },
        preparse: function (string) {
            return string
                .replace(/[١٢٣٤٥٦٧٨٩٠]/g, function (match) {
                    return numberMap[match];
                })
                .replace(/،/g, ',');
        },
        postformat: function (string) {
            return string
                .replace(/\d/g, function (match) {
                    return symbolMap$1[match];
                })
                .replace(/,/g, '،');
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ar-tn', {
        months: 'جانفي_فيفري_مارس_أفريل_ماي_جوان_جويلية_أوت_سبتمبر_أكتوبر_نوفمبر_ديسمبر'.split(
            '_'
        ),
        monthsShort:
            'جانفي_فيفري_مارس_أفريل_ماي_جوان_جويلية_أوت_سبتمبر_أكتوبر_نوفمبر_ديسمبر'.split(
                '_'
            ),
        weekdays: 'الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت'.split('_'),
        weekdaysShort: 'أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت'.split('_'),
        weekdaysMin: 'ح_ن_ث_ر_خ_ج_س'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[اليوم على الساعة] LT',
            nextDay: '[غدا على الساعة] LT',
            nextWeek: 'dddd [على الساعة] LT',
            lastDay: '[أمس على الساعة] LT',
            lastWeek: 'dddd [على الساعة] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'في %s',
            past: 'منذ %s',
            s: 'ثوان',
            ss: '%d ثانية',
            m: 'دقيقة',
            mm: '%d دقائق',
            h: 'ساعة',
            hh: '%d ساعات',
            d: 'يوم',
            dd: '%d أيام',
            M: 'شهر',
            MM: '%d أشهر',
            y: 'سنة',
            yy: '%d سنوات',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$2 = {
            1: '١',
            2: '٢',
            3: '٣',
            4: '٤',
            5: '٥',
            6: '٦',
            7: '٧',
            8: '٨',
            9: '٩',
            0: '٠',
        },
        numberMap$1 = {
            '١': '1',
            '٢': '2',
            '٣': '3',
            '٤': '4',
            '٥': '5',
            '٦': '6',
            '٧': '7',
            '٨': '8',
            '٩': '9',
            '٠': '0',
        },
        pluralForm$2 = function (n) {
            return n === 0
                ? 0
                : n === 1
                ? 1
                : n === 2
                ? 2
                : n % 100 >= 3 && n % 100 <= 10
                ? 3
                : n % 100 >= 11
                ? 4
                : 5;
        },
        plurals$2 = {
            s: [
                'أقل من ثانية',
                'ثانية واحدة',
                ['ثانيتان', 'ثانيتين'],
                '%d ثوان',
                '%d ثانية',
                '%d ثانية',
            ],
            m: [
                'أقل من دقيقة',
                'دقيقة واحدة',
                ['دقيقتان', 'دقيقتين'],
                '%d دقائق',
                '%d دقيقة',
                '%d دقيقة',
            ],
            h: [
                'أقل من ساعة',
                'ساعة واحدة',
                ['ساعتان', 'ساعتين'],
                '%d ساعات',
                '%d ساعة',
                '%d ساعة',
            ],
            d: [
                'أقل من يوم',
                'يوم واحد',
                ['يومان', 'يومين'],
                '%d أيام',
                '%d يومًا',
                '%d يوم',
            ],
            M: [
                'أقل من شهر',
                'شهر واحد',
                ['شهران', 'شهرين'],
                '%d أشهر',
                '%d شهرا',
                '%d شهر',
            ],
            y: [
                'أقل من عام',
                'عام واحد',
                ['عامان', 'عامين'],
                '%d أعوام',
                '%d عامًا',
                '%d عام',
            ],
        },
        pluralize$2 = function (u) {
            return function (number, withoutSuffix, string, isFuture) {
                var f = pluralForm$2(number),
                    str = plurals$2[u][pluralForm$2(number)];
                if (f === 2) {
                    str = str[withoutSuffix ? 0 : 1];
                }
                return str.replace(/%d/i, number);
            };
        },
        months$3 = [
            'يناير',
            'فبراير',
            'مارس',
            'أبريل',
            'مايو',
            'يونيو',
            'يوليو',
            'أغسطس',
            'سبتمبر',
            'أكتوبر',
            'نوفمبر',
            'ديسمبر',
        ];

    hooks.defineLocale('ar', {
        months: months$3,
        monthsShort: months$3,
        weekdays: 'الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت'.split('_'),
        weekdaysShort: 'أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت'.split('_'),
        weekdaysMin: 'ح_ن_ث_ر_خ_ج_س'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'D/\u200FM/\u200FYYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        meridiemParse: /ص|م/,
        isPM: function (input) {
            return 'م' === input;
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ص';
            } else {
                return 'م';
            }
        },
        calendar: {
            sameDay: '[اليوم عند الساعة] LT',
            nextDay: '[غدًا عند الساعة] LT',
            nextWeek: 'dddd [عند الساعة] LT',
            lastDay: '[أمس عند الساعة] LT',
            lastWeek: 'dddd [عند الساعة] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'بعد %s',
            past: 'منذ %s',
            s: pluralize$2('s'),
            ss: pluralize$2('s'),
            m: pluralize$2('m'),
            mm: pluralize$2('m'),
            h: pluralize$2('h'),
            hh: pluralize$2('h'),
            d: pluralize$2('d'),
            dd: pluralize$2('d'),
            M: pluralize$2('M'),
            MM: pluralize$2('M'),
            y: pluralize$2('y'),
            yy: pluralize$2('y'),
        },
        preparse: function (string) {
            return string
                .replace(/[١٢٣٤٥٦٧٨٩٠]/g, function (match) {
                    return numberMap$1[match];
                })
                .replace(/،/g, ',');
        },
        postformat: function (string) {
            return string
                .replace(/\d/g, function (match) {
                    return symbolMap$2[match];
                })
                .replace(/,/g, '،');
        },
        week: {
            dow: 6, // Saturday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var suffixes = {
        1: '-inci',
        5: '-inci',
        8: '-inci',
        70: '-inci',
        80: '-inci',
        2: '-nci',
        7: '-nci',
        20: '-nci',
        50: '-nci',
        3: '-üncü',
        4: '-üncü',
        100: '-üncü',
        6: '-ncı',
        9: '-uncu',
        10: '-uncu',
        30: '-uncu',
        60: '-ıncı',
        90: '-ıncı',
    };

    hooks.defineLocale('az', {
        months: 'yanvar_fevral_mart_aprel_may_iyun_iyul_avqust_sentyabr_oktyabr_noyabr_dekabr'.split(
            '_'
        ),
        monthsShort: 'yan_fev_mar_apr_may_iyn_iyl_avq_sen_okt_noy_dek'.split('_'),
        weekdays:
            'Bazar_Bazar ertəsi_Çərşənbə axşamı_Çərşənbə_Cümə axşamı_Cümə_Şənbə'.split(
                '_'
            ),
        weekdaysShort: 'Baz_BzE_ÇAx_Çər_CAx_Cüm_Şən'.split('_'),
        weekdaysMin: 'Bz_BE_ÇA_Çə_CA_Cü_Şə'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[bugün saat] LT',
            nextDay: '[sabah saat] LT',
            nextWeek: '[gələn həftə] dddd [saat] LT',
            lastDay: '[dünən] LT',
            lastWeek: '[keçən həftə] dddd [saat] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s sonra',
            past: '%s əvvəl',
            s: 'bir neçə saniyə',
            ss: '%d saniyə',
            m: 'bir dəqiqə',
            mm: '%d dəqiqə',
            h: 'bir saat',
            hh: '%d saat',
            d: 'bir gün',
            dd: '%d gün',
            M: 'bir ay',
            MM: '%d ay',
            y: 'bir il',
            yy: '%d il',
        },
        meridiemParse: /gecə|səhər|gündüz|axşam/,
        isPM: function (input) {
            return /^(gündüz|axşam)$/.test(input);
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'gecə';
            } else if (hour < 12) {
                return 'səhər';
            } else if (hour < 17) {
                return 'gündüz';
            } else {
                return 'axşam';
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(ıncı|inci|nci|üncü|ncı|uncu)/,
        ordinal: function (number) {
            if (number === 0) {
                // special case for zero
                return number + '-ıncı';
            }
            var a = number % 10,
                b = (number % 100) - a,
                c = number >= 100 ? 100 : null;
            return number + (suffixes[a] || suffixes[b] || suffixes[c]);
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function plural(word, num) {
        var forms = word.split('_');
        return num % 10 === 1 && num % 100 !== 11
            ? forms[0]
            : num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20)
            ? forms[1]
            : forms[2];
    }
    function relativeTimeWithPlural(number, withoutSuffix, key) {
        var format = {
            ss: withoutSuffix ? 'секунда_секунды_секунд' : 'секунду_секунды_секунд',
            mm: withoutSuffix ? 'хвіліна_хвіліны_хвілін' : 'хвіліну_хвіліны_хвілін',
            hh: withoutSuffix ? 'гадзіна_гадзіны_гадзін' : 'гадзіну_гадзіны_гадзін',
            dd: 'дзень_дні_дзён',
            MM: 'месяц_месяцы_месяцаў',
            yy: 'год_гады_гадоў',
        };
        if (key === 'm') {
            return withoutSuffix ? 'хвіліна' : 'хвіліну';
        } else if (key === 'h') {
            return withoutSuffix ? 'гадзіна' : 'гадзіну';
        } else {
            return number + ' ' + plural(format[key], +number);
        }
    }

    hooks.defineLocale('be', {
        months: {
            format: 'студзеня_лютага_сакавіка_красавіка_траўня_чэрвеня_ліпеня_жніўня_верасня_кастрычніка_лістапада_снежня'.split(
                '_'
            ),
            standalone:
                'студзень_люты_сакавік_красавік_травень_чэрвень_ліпень_жнівень_верасень_кастрычнік_лістапад_снежань'.split(
                    '_'
                ),
        },
        monthsShort:
            'студ_лют_сак_крас_трав_чэрв_ліп_жнів_вер_каст_ліст_снеж'.split('_'),
        weekdays: {
            format: 'нядзелю_панядзелак_аўторак_сераду_чацвер_пятніцу_суботу'.split(
                '_'
            ),
            standalone:
                'нядзеля_панядзелак_аўторак_серада_чацвер_пятніца_субота'.split(
                    '_'
                ),
            isFormat: /\[ ?[Ууў] ?(?:мінулую|наступную)? ?\] ?dddd/,
        },
        weekdaysShort: 'нд_пн_ат_ср_чц_пт_сб'.split('_'),
        weekdaysMin: 'нд_пн_ат_ср_чц_пт_сб'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY г.',
            LLL: 'D MMMM YYYY г., HH:mm',
            LLLL: 'dddd, D MMMM YYYY г., HH:mm',
        },
        calendar: {
            sameDay: '[Сёння ў] LT',
            nextDay: '[Заўтра ў] LT',
            lastDay: '[Учора ў] LT',
            nextWeek: function () {
                return '[У] dddd [ў] LT';
            },
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                    case 3:
                    case 5:
                    case 6:
                        return '[У мінулую] dddd [ў] LT';
                    case 1:
                    case 2:
                    case 4:
                        return '[У мінулы] dddd [ў] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'праз %s',
            past: '%s таму',
            s: 'некалькі секунд',
            m: relativeTimeWithPlural,
            mm: relativeTimeWithPlural,
            h: relativeTimeWithPlural,
            hh: relativeTimeWithPlural,
            d: 'дзень',
            dd: relativeTimeWithPlural,
            M: 'месяц',
            MM: relativeTimeWithPlural,
            y: 'год',
            yy: relativeTimeWithPlural,
        },
        meridiemParse: /ночы|раніцы|дня|вечара/,
        isPM: function (input) {
            return /^(дня|вечара)$/.test(input);
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'ночы';
            } else if (hour < 12) {
                return 'раніцы';
            } else if (hour < 17) {
                return 'дня';
            } else {
                return 'вечара';
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(і|ы|га)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'M':
                case 'd':
                case 'DDD':
                case 'w':
                case 'W':
                    return (number % 10 === 2 || number % 10 === 3) &&
                        number % 100 !== 12 &&
                        number % 100 !== 13
                        ? number + '-і'
                        : number + '-ы';
                case 'D':
                    return number + '-га';
                default:
                    return number;
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('bg', {
        months: 'януари_февруари_март_април_май_юни_юли_август_септември_октомври_ноември_декември'.split(
            '_'
        ),
        monthsShort: 'яну_фев_мар_апр_май_юни_юли_авг_сеп_окт_ное_дек'.split('_'),
        weekdays: 'неделя_понеделник_вторник_сряда_четвъртък_петък_събота'.split(
            '_'
        ),
        weekdaysShort: 'нед_пон_вто_сря_чет_пет_съб'.split('_'),
        weekdaysMin: 'нд_пн_вт_ср_чт_пт_сб'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'D.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY H:mm',
            LLLL: 'dddd, D MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[Днес в] LT',
            nextDay: '[Утре в] LT',
            nextWeek: 'dddd [в] LT',
            lastDay: '[Вчера в] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                    case 3:
                    case 6:
                        return '[Миналата] dddd [в] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[Миналия] dddd [в] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'след %s',
            past: 'преди %s',
            s: 'няколко секунди',
            ss: '%d секунди',
            m: 'минута',
            mm: '%d минути',
            h: 'час',
            hh: '%d часа',
            d: 'ден',
            dd: '%d дена',
            w: 'седмица',
            ww: '%d седмици',
            M: 'месец',
            MM: '%d месеца',
            y: 'година',
            yy: '%d години',
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(ев|ен|ти|ви|ри|ми)/,
        ordinal: function (number) {
            var lastDigit = number % 10,
                last2Digits = number % 100;
            if (number === 0) {
                return number + '-ев';
            } else if (last2Digits === 0) {
                return number + '-ен';
            } else if (last2Digits > 10 && last2Digits < 20) {
                return number + '-ти';
            } else if (lastDigit === 1) {
                return number + '-ви';
            } else if (lastDigit === 2) {
                return number + '-ри';
            } else if (lastDigit === 7 || lastDigit === 8) {
                return number + '-ми';
            } else {
                return number + '-ти';
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('bm', {
        months: 'Zanwuyekalo_Fewuruyekalo_Marisikalo_Awirilikalo_Mɛkalo_Zuwɛnkalo_Zuluyekalo_Utikalo_Sɛtanburukalo_ɔkutɔburukalo_Nowanburukalo_Desanburukalo'.split(
            '_'
        ),
        monthsShort: 'Zan_Few_Mar_Awi_Mɛ_Zuw_Zul_Uti_Sɛt_ɔku_Now_Des'.split('_'),
        weekdays: 'Kari_Ntɛnɛn_Tarata_Araba_Alamisa_Juma_Sibiri'.split('_'),
        weekdaysShort: 'Kar_Ntɛ_Tar_Ara_Ala_Jum_Sib'.split('_'),
        weekdaysMin: 'Ka_Nt_Ta_Ar_Al_Ju_Si'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'MMMM [tile] D [san] YYYY',
            LLL: 'MMMM [tile] D [san] YYYY [lɛrɛ] HH:mm',
            LLLL: 'dddd MMMM [tile] D [san] YYYY [lɛrɛ] HH:mm',
        },
        calendar: {
            sameDay: '[Bi lɛrɛ] LT',
            nextDay: '[Sini lɛrɛ] LT',
            nextWeek: 'dddd [don lɛrɛ] LT',
            lastDay: '[Kunu lɛrɛ] LT',
            lastWeek: 'dddd [tɛmɛnen lɛrɛ] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s kɔnɔ',
            past: 'a bɛ %s bɔ',
            s: 'sanga dama dama',
            ss: 'sekondi %d',
            m: 'miniti kelen',
            mm: 'miniti %d',
            h: 'lɛrɛ kelen',
            hh: 'lɛrɛ %d',
            d: 'tile kelen',
            dd: 'tile %d',
            M: 'kalo kelen',
            MM: 'kalo %d',
            y: 'san kelen',
            yy: 'san %d',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$3 = {
            1: '১',
            2: '২',
            3: '৩',
            4: '৪',
            5: '৫',
            6: '৬',
            7: '৭',
            8: '৮',
            9: '৯',
            0: '০',
        },
        numberMap$2 = {
            '১': '1',
            '২': '2',
            '৩': '3',
            '৪': '4',
            '৫': '5',
            '৬': '6',
            '৭': '7',
            '৮': '8',
            '৯': '9',
            '০': '0',
        };

    hooks.defineLocale('bn-bd', {
        months: 'জানুয়ারি_ফেব্রুয়ারি_মার্চ_এপ্রিল_মে_জুন_জুলাই_আগস্ট_সেপ্টেম্বর_অক্টোবর_নভেম্বর_ডিসেম্বর'.split(
            '_'
        ),
        monthsShort:
            'জানু_ফেব্রু_মার্চ_এপ্রিল_মে_জুন_জুলাই_আগস্ট_সেপ্ট_অক্টো_নভে_ডিসে'.split(
                '_'
            ),
        weekdays: 'রবিবার_সোমবার_মঙ্গলবার_বুধবার_বৃহস্পতিবার_শুক্রবার_শনিবার'.split(
            '_'
        ),
        weekdaysShort: 'রবি_সোম_মঙ্গল_বুধ_বৃহস্পতি_শুক্র_শনি'.split('_'),
        weekdaysMin: 'রবি_সোম_মঙ্গল_বুধ_বৃহ_শুক্র_শনি'.split('_'),
        longDateFormat: {
            LT: 'A h:mm সময়',
            LTS: 'A h:mm:ss সময়',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm সময়',
            LLLL: 'dddd, D MMMM YYYY, A h:mm সময়',
        },
        calendar: {
            sameDay: '[আজ] LT',
            nextDay: '[আগামীকাল] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[গতকাল] LT',
            lastWeek: '[গত] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s পরে',
            past: '%s আগে',
            s: 'কয়েক সেকেন্ড',
            ss: '%d সেকেন্ড',
            m: 'এক মিনিট',
            mm: '%d মিনিট',
            h: 'এক ঘন্টা',
            hh: '%d ঘন্টা',
            d: 'এক দিন',
            dd: '%d দিন',
            M: 'এক মাস',
            MM: '%d মাস',
            y: 'এক বছর',
            yy: '%d বছর',
        },
        preparse: function (string) {
            return string.replace(/[১২৩৪৫৬৭৮৯০]/g, function (match) {
                return numberMap$2[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$3[match];
            });
        },

        meridiemParse: /রাত|ভোর|সকাল|দুপুর|বিকাল|সন্ধ্যা|রাত/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'রাত') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'ভোর') {
                return hour;
            } else if (meridiem === 'সকাল') {
                return hour;
            } else if (meridiem === 'দুপুর') {
                return hour >= 3 ? hour : hour + 12;
            } else if (meridiem === 'বিকাল') {
                return hour + 12;
            } else if (meridiem === 'সন্ধ্যা') {
                return hour + 12;
            }
        },

        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'রাত';
            } else if (hour < 6) {
                return 'ভোর';
            } else if (hour < 12) {
                return 'সকাল';
            } else if (hour < 15) {
                return 'দুপুর';
            } else if (hour < 18) {
                return 'বিকাল';
            } else if (hour < 20) {
                return 'সন্ধ্যা';
            } else {
                return 'রাত';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$4 = {
            1: '১',
            2: '২',
            3: '৩',
            4: '৪',
            5: '৫',
            6: '৬',
            7: '৭',
            8: '৮',
            9: '৯',
            0: '০',
        },
        numberMap$3 = {
            '১': '1',
            '২': '2',
            '৩': '3',
            '৪': '4',
            '৫': '5',
            '৬': '6',
            '৭': '7',
            '৮': '8',
            '৯': '9',
            '০': '0',
        };

    hooks.defineLocale('bn', {
        months: 'জানুয়ারি_ফেব্রুয়ারি_মার্চ_এপ্রিল_মে_জুন_জুলাই_আগস্ট_সেপ্টেম্বর_অক্টোবর_নভেম্বর_ডিসেম্বর'.split(
            '_'
        ),
        monthsShort:
            'জানু_ফেব্রু_মার্চ_এপ্রিল_মে_জুন_জুলাই_আগস্ট_সেপ্ট_অক্টো_নভে_ডিসে'.split(
                '_'
            ),
        weekdays: 'রবিবার_সোমবার_মঙ্গলবার_বুধবার_বৃহস্পতিবার_শুক্রবার_শনিবার'.split(
            '_'
        ),
        weekdaysShort: 'রবি_সোম_মঙ্গল_বুধ_বৃহস্পতি_শুক্র_শনি'.split('_'),
        weekdaysMin: 'রবি_সোম_মঙ্গল_বুধ_বৃহ_শুক্র_শনি'.split('_'),
        longDateFormat: {
            LT: 'A h:mm সময়',
            LTS: 'A h:mm:ss সময়',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm সময়',
            LLLL: 'dddd, D MMMM YYYY, A h:mm সময়',
        },
        calendar: {
            sameDay: '[আজ] LT',
            nextDay: '[আগামীকাল] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[গতকাল] LT',
            lastWeek: '[গত] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s পরে',
            past: '%s আগে',
            s: 'কয়েক সেকেন্ড',
            ss: '%d সেকেন্ড',
            m: 'এক মিনিট',
            mm: '%d মিনিট',
            h: 'এক ঘন্টা',
            hh: '%d ঘন্টা',
            d: 'এক দিন',
            dd: '%d দিন',
            M: 'এক মাস',
            MM: '%d মাস',
            y: 'এক বছর',
            yy: '%d বছর',
        },
        preparse: function (string) {
            return string.replace(/[১২৩৪৫৬৭৮৯০]/g, function (match) {
                return numberMap$3[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$4[match];
            });
        },
        meridiemParse: /রাত|সকাল|দুপুর|বিকাল|রাত/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (
                (meridiem === 'রাত' && hour >= 4) ||
                (meridiem === 'দুপুর' && hour < 5) ||
                meridiem === 'বিকাল'
            ) {
                return hour + 12;
            } else {
                return hour;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'রাত';
            } else if (hour < 10) {
                return 'সকাল';
            } else if (hour < 17) {
                return 'দুপুর';
            } else if (hour < 20) {
                return 'বিকাল';
            } else {
                return 'রাত';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$5 = {
            1: '༡',
            2: '༢',
            3: '༣',
            4: '༤',
            5: '༥',
            6: '༦',
            7: '༧',
            8: '༨',
            9: '༩',
            0: '༠',
        },
        numberMap$4 = {
            '༡': '1',
            '༢': '2',
            '༣': '3',
            '༤': '4',
            '༥': '5',
            '༦': '6',
            '༧': '7',
            '༨': '8',
            '༩': '9',
            '༠': '0',
        };

    hooks.defineLocale('bo', {
        months: 'ཟླ་བ་དང་པོ_ཟླ་བ་གཉིས་པ_ཟླ་བ་གསུམ་པ_ཟླ་བ་བཞི་པ_ཟླ་བ་ལྔ་པ_ཟླ་བ་དྲུག་པ_ཟླ་བ་བདུན་པ_ཟླ་བ་བརྒྱད་པ_ཟླ་བ་དགུ་པ_ཟླ་བ་བཅུ་པ_ཟླ་བ་བཅུ་གཅིག་པ_ཟླ་བ་བཅུ་གཉིས་པ'.split(
            '_'
        ),
        monthsShort:
            'ཟླ་1_ཟླ་2_ཟླ་3_ཟླ་4_ཟླ་5_ཟླ་6_ཟླ་7_ཟླ་8_ཟླ་9_ཟླ་10_ཟླ་11_ཟླ་12'.split(
                '_'
            ),
        monthsShortRegex: /^(ཟླ་\d{1,2})/,
        monthsParseExact: true,
        weekdays:
            'གཟའ་ཉི་མ་_གཟའ་ཟླ་བ་_གཟའ་མིག་དམར་_གཟའ་ལྷག་པ་_གཟའ་ཕུར་བུ_གཟའ་པ་སངས་_གཟའ་སྤེན་པ་'.split(
                '_'
            ),
        weekdaysShort: 'ཉི་མ་_ཟླ་བ་_མིག་དམར་_ལྷག་པ་_ཕུར་བུ_པ་སངས་_སྤེན་པ་'.split(
            '_'
        ),
        weekdaysMin: 'ཉི_ཟླ_མིག_ལྷག_ཕུར_སངས_སྤེན'.split('_'),
        longDateFormat: {
            LT: 'A h:mm',
            LTS: 'A h:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm',
            LLLL: 'dddd, D MMMM YYYY, A h:mm',
        },
        calendar: {
            sameDay: '[དི་རིང] LT',
            nextDay: '[སང་ཉིན] LT',
            nextWeek: '[བདུན་ཕྲག་རྗེས་མ], LT',
            lastDay: '[ཁ་སང] LT',
            lastWeek: '[བདུན་ཕྲག་མཐའ་མ] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s ལ་',
            past: '%s སྔན་ལ',
            s: 'ལམ་སང',
            ss: '%d སྐར་ཆ།',
            m: 'སྐར་མ་གཅིག',
            mm: '%d སྐར་མ',
            h: 'ཆུ་ཚོད་གཅིག',
            hh: '%d ཆུ་ཚོད',
            d: 'ཉིན་གཅིག',
            dd: '%d ཉིན་',
            M: 'ཟླ་བ་གཅིག',
            MM: '%d ཟླ་བ',
            y: 'ལོ་གཅིག',
            yy: '%d ལོ',
        },
        preparse: function (string) {
            return string.replace(/[༡༢༣༤༥༦༧༨༩༠]/g, function (match) {
                return numberMap$4[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$5[match];
            });
        },
        meridiemParse: /མཚན་མོ|ཞོགས་ཀས|ཉིན་གུང|དགོང་དག|མཚན་མོ/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (
                (meridiem === 'མཚན་མོ' && hour >= 4) ||
                (meridiem === 'ཉིན་གུང' && hour < 5) ||
                meridiem === 'དགོང་དག'
            ) {
                return hour + 12;
            } else {
                return hour;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'མཚན་མོ';
            } else if (hour < 10) {
                return 'ཞོགས་ཀས';
            } else if (hour < 17) {
                return 'ཉིན་གུང';
            } else if (hour < 20) {
                return 'དགོང་དག';
            } else {
                return 'མཚན་མོ';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function relativeTimeWithMutation(number, withoutSuffix, key) {
        var format = {
            mm: 'munutenn',
            MM: 'miz',
            dd: 'devezh',
        };
        return number + ' ' + mutation(format[key], number);
    }
    function specialMutationForYears(number) {
        switch (lastNumber(number)) {
            case 1:
            case 3:
            case 4:
            case 5:
            case 9:
                return number + ' bloaz';
            default:
                return number + ' vloaz';
        }
    }
    function lastNumber(number) {
        if (number > 9) {
            return lastNumber(number % 10);
        }
        return number;
    }
    function mutation(text, number) {
        if (number === 2) {
            return softMutation(text);
        }
        return text;
    }
    function softMutation(text) {
        var mutationTable = {
            m: 'v',
            b: 'v',
            d: 'z',
        };
        if (mutationTable[text.charAt(0)] === undefined) {
            return text;
        }
        return mutationTable[text.charAt(0)] + text.substring(1);
    }

    var monthsParse = [
            /^gen/i,
            /^c[ʼ\']hwe/i,
            /^meu/i,
            /^ebr/i,
            /^mae/i,
            /^(mez|eve)/i,
            /^gou/i,
            /^eos/i,
            /^gwe/i,
            /^her/i,
            /^du/i,
            /^ker/i,
        ],
        monthsRegex$1 =
            /^(genver|c[ʼ\']hwevrer|meurzh|ebrel|mae|mezheven|gouere|eost|gwengolo|here|du|kerzu|gen|c[ʼ\']hwe|meu|ebr|mae|eve|gou|eos|gwe|her|du|ker)/i,
        monthsStrictRegex =
            /^(genver|c[ʼ\']hwevrer|meurzh|ebrel|mae|mezheven|gouere|eost|gwengolo|here|du|kerzu)/i,
        monthsShortStrictRegex =
            /^(gen|c[ʼ\']hwe|meu|ebr|mae|eve|gou|eos|gwe|her|du|ker)/i,
        fullWeekdaysParse = [
            /^sul/i,
            /^lun/i,
            /^meurzh/i,
            /^merc[ʼ\']her/i,
            /^yaou/i,
            /^gwener/i,
            /^sadorn/i,
        ],
        shortWeekdaysParse = [
            /^Sul/i,
            /^Lun/i,
            /^Meu/i,
            /^Mer/i,
            /^Yao/i,
            /^Gwe/i,
            /^Sad/i,
        ],
        minWeekdaysParse = [
            /^Su/i,
            /^Lu/i,
            /^Me([^r]|$)/i,
            /^Mer/i,
            /^Ya/i,
            /^Gw/i,
            /^Sa/i,
        ];

    hooks.defineLocale('br', {
        months: 'Genver_Cʼhwevrer_Meurzh_Ebrel_Mae_Mezheven_Gouere_Eost_Gwengolo_Here_Du_Kerzu'.split(
            '_'
        ),
        monthsShort: 'Gen_Cʼhwe_Meu_Ebr_Mae_Eve_Gou_Eos_Gwe_Her_Du_Ker'.split('_'),
        weekdays: 'Sul_Lun_Meurzh_Mercʼher_Yaou_Gwener_Sadorn'.split('_'),
        weekdaysShort: 'Sul_Lun_Meu_Mer_Yao_Gwe_Sad'.split('_'),
        weekdaysMin: 'Su_Lu_Me_Mer_Ya_Gw_Sa'.split('_'),
        weekdaysParse: minWeekdaysParse,
        fullWeekdaysParse: fullWeekdaysParse,
        shortWeekdaysParse: shortWeekdaysParse,
        minWeekdaysParse: minWeekdaysParse,

        monthsRegex: monthsRegex$1,
        monthsShortRegex: monthsRegex$1,
        monthsStrictRegex: monthsStrictRegex,
        monthsShortStrictRegex: monthsShortStrictRegex,
        monthsParse: monthsParse,
        longMonthsParse: monthsParse,
        shortMonthsParse: monthsParse,

        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D [a viz] MMMM YYYY',
            LLL: 'D [a viz] MMMM YYYY HH:mm',
            LLLL: 'dddd, D [a viz] MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Hiziv da] LT',
            nextDay: '[Warcʼhoazh da] LT',
            nextWeek: 'dddd [da] LT',
            lastDay: '[Decʼh da] LT',
            lastWeek: 'dddd [paset da] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'a-benn %s',
            past: '%s ʼzo',
            s: 'un nebeud segondennoù',
            ss: '%d eilenn',
            m: 'ur vunutenn',
            mm: relativeTimeWithMutation,
            h: 'un eur',
            hh: '%d eur',
            d: 'un devezh',
            dd: relativeTimeWithMutation,
            M: 'ur miz',
            MM: relativeTimeWithMutation,
            y: 'ur bloaz',
            yy: specialMutationForYears,
        },
        dayOfMonthOrdinalParse: /\d{1,2}(añ|vet)/,
        ordinal: function (number) {
            var output = number === 1 ? 'añ' : 'vet';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
        meridiemParse: /a.m.|g.m./, // goude merenn | a-raok merenn
        isPM: function (token) {
            return token === 'g.m.';
        },
        meridiem: function (hour, minute, isLower) {
            return hour < 12 ? 'a.m.' : 'g.m.';
        },
    });

    //! moment.js locale configuration

    function translate(number, withoutSuffix, key) {
        var result = number + ' ';
        switch (key) {
            case 'ss':
                if (number === 1) {
                    result += 'sekunda';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'sekunde';
                } else {
                    result += 'sekundi';
                }
                return result;
            case 'm':
                return withoutSuffix ? 'jedna minuta' : 'jedne minute';
            case 'mm':
                if (number === 1) {
                    result += 'minuta';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'minute';
                } else {
                    result += 'minuta';
                }
                return result;
            case 'h':
                return withoutSuffix ? 'jedan sat' : 'jednog sata';
            case 'hh':
                if (number === 1) {
                    result += 'sat';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'sata';
                } else {
                    result += 'sati';
                }
                return result;
            case 'dd':
                if (number === 1) {
                    result += 'dan';
                } else {
                    result += 'dana';
                }
                return result;
            case 'MM':
                if (number === 1) {
                    result += 'mjesec';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'mjeseca';
                } else {
                    result += 'mjeseci';
                }
                return result;
            case 'yy':
                if (number === 1) {
                    result += 'godina';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'godine';
                } else {
                    result += 'godina';
                }
                return result;
        }
    }

    hooks.defineLocale('bs', {
        months: 'januar_februar_mart_april_maj_juni_juli_august_septembar_oktobar_novembar_decembar'.split(
            '_'
        ),
        monthsShort:
            'jan._feb._mar._apr._maj._jun._jul._aug._sep._okt._nov._dec.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'nedjelja_ponedjeljak_utorak_srijeda_četvrtak_petak_subota'.split(
            '_'
        ),
        weekdaysShort: 'ned._pon._uto._sri._čet._pet._sub.'.split('_'),
        weekdaysMin: 'ne_po_ut_sr_če_pe_su'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY H:mm',
            LLLL: 'dddd, D. MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[danas u] LT',
            nextDay: '[sutra u] LT',
            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[u] [nedjelju] [u] LT';
                    case 3:
                        return '[u] [srijedu] [u] LT';
                    case 6:
                        return '[u] [subotu] [u] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[u] dddd [u] LT';
                }
            },
            lastDay: '[jučer u] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                    case 3:
                        return '[prošlu] dddd [u] LT';
                    case 6:
                        return '[prošle] [subote] [u] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[prošli] dddd [u] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'za %s',
            past: 'prije %s',
            s: 'par sekundi',
            ss: translate,
            m: translate,
            mm: translate,
            h: translate,
            hh: translate,
            d: 'dan',
            dd: translate,
            M: 'mjesec',
            MM: translate,
            y: 'godinu',
            yy: translate,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ca', {
        months: {
            standalone:
                'gener_febrer_març_abril_maig_juny_juliol_agost_setembre_octubre_novembre_desembre'.split(
                    '_'
                ),
            format: "de gener_de febrer_de març_d'abril_de maig_de juny_de juliol_d'agost_de setembre_d'octubre_de novembre_de desembre".split(
                '_'
            ),
            isFormat: /D[oD]?(\s)+MMMM/,
        },
        monthsShort:
            'gen._febr._març_abr._maig_juny_jul._ag._set._oct._nov._des.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays:
            'diumenge_dilluns_dimarts_dimecres_dijous_divendres_dissabte'.split(
                '_'
            ),
        weekdaysShort: 'dg._dl._dt._dc._dj._dv._ds.'.split('_'),
        weekdaysMin: 'dg_dl_dt_dc_dj_dv_ds'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM [de] YYYY',
            ll: 'D MMM YYYY',
            LLL: 'D MMMM [de] YYYY [a les] H:mm',
            lll: 'D MMM YYYY, H:mm',
            LLLL: 'dddd D MMMM [de] YYYY [a les] H:mm',
            llll: 'ddd D MMM YYYY, H:mm',
        },
        calendar: {
            sameDay: function () {
                return '[avui a ' + (this.hours() !== 1 ? 'les' : 'la') + '] LT';
            },
            nextDay: function () {
                return '[demà a ' + (this.hours() !== 1 ? 'les' : 'la') + '] LT';
            },
            nextWeek: function () {
                return 'dddd [a ' + (this.hours() !== 1 ? 'les' : 'la') + '] LT';
            },
            lastDay: function () {
                return '[ahir a ' + (this.hours() !== 1 ? 'les' : 'la') + '] LT';
            },
            lastWeek: function () {
                return (
                    '[el] dddd [passat a ' +
                    (this.hours() !== 1 ? 'les' : 'la') +
                    '] LT'
                );
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: "d'aquí %s",
            past: 'fa %s',
            s: 'uns segons',
            ss: '%d segons',
            m: 'un minut',
            mm: '%d minuts',
            h: 'una hora',
            hh: '%d hores',
            d: 'un dia',
            dd: '%d dies',
            M: 'un mes',
            MM: '%d mesos',
            y: 'un any',
            yy: '%d anys',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(r|n|t|è|a)/,
        ordinal: function (number, period) {
            var output =
                number === 1
                    ? 'r'
                    : number === 2
                    ? 'n'
                    : number === 3
                    ? 'r'
                    : number === 4
                    ? 't'
                    : 'è';
            if (period === 'w' || period === 'W') {
                output = 'a';
            }
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var months$4 = {
            format: 'leden_únor_březen_duben_květen_červen_červenec_srpen_září_říjen_listopad_prosinec'.split(
                '_'
            ),
            standalone:
                'ledna_února_března_dubna_května_června_července_srpna_září_října_listopadu_prosince'.split(
                    '_'
                ),
        },
        monthsShort = 'led_úno_bře_dub_kvě_čvn_čvc_srp_zář_říj_lis_pro'.split('_'),
        monthsParse$1 = [
            /^led/i,
            /^úno/i,
            /^bře/i,
            /^dub/i,
            /^kvě/i,
            /^(čvn|červen$|června)/i,
            /^(čvc|červenec|července)/i,
            /^srp/i,
            /^zář/i,
            /^říj/i,
            /^lis/i,
            /^pro/i,
        ],
        // NOTE: 'červen' is substring of 'červenec'; therefore 'červenec' must precede 'červen' in the regex to be fully matched.
        // Otherwise parser matches '1. červenec' as '1. červen' + 'ec'.
        monthsRegex$2 =
            /^(leden|únor|březen|duben|květen|červenec|července|červen|června|srpen|září|říjen|listopad|prosinec|led|úno|bře|dub|kvě|čvn|čvc|srp|zář|říj|lis|pro)/i;

    function plural$1(n) {
        return n > 1 && n < 5 && ~~(n / 10) !== 1;
    }
    function translate$1(number, withoutSuffix, key, isFuture) {
        var result = number + ' ';
        switch (key) {
            case 's': // a few seconds / in a few seconds / a few seconds ago
                return withoutSuffix || isFuture ? 'pár sekund' : 'pár sekundami';
            case 'ss': // 9 seconds / in 9 seconds / 9 seconds ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$1(number) ? 'sekundy' : 'sekund');
                } else {
                    return result + 'sekundami';
                }
            case 'm': // a minute / in a minute / a minute ago
                return withoutSuffix ? 'minuta' : isFuture ? 'minutu' : 'minutou';
            case 'mm': // 9 minutes / in 9 minutes / 9 minutes ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$1(number) ? 'minuty' : 'minut');
                } else {
                    return result + 'minutami';
                }
            case 'h': // an hour / in an hour / an hour ago
                return withoutSuffix ? 'hodina' : isFuture ? 'hodinu' : 'hodinou';
            case 'hh': // 9 hours / in 9 hours / 9 hours ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$1(number) ? 'hodiny' : 'hodin');
                } else {
                    return result + 'hodinami';
                }
            case 'd': // a day / in a day / a day ago
                return withoutSuffix || isFuture ? 'den' : 'dnem';
            case 'dd': // 9 days / in 9 days / 9 days ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$1(number) ? 'dny' : 'dní');
                } else {
                    return result + 'dny';
                }
            case 'M': // a month / in a month / a month ago
                return withoutSuffix || isFuture ? 'měsíc' : 'měsícem';
            case 'MM': // 9 months / in 9 months / 9 months ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$1(number) ? 'měsíce' : 'měsíců');
                } else {
                    return result + 'měsíci';
                }
            case 'y': // a year / in a year / a year ago
                return withoutSuffix || isFuture ? 'rok' : 'rokem';
            case 'yy': // 9 years / in 9 years / 9 years ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$1(number) ? 'roky' : 'let');
                } else {
                    return result + 'lety';
                }
        }
    }

    hooks.defineLocale('cs', {
        months: months$4,
        monthsShort: monthsShort,
        monthsRegex: monthsRegex$2,
        monthsShortRegex: monthsRegex$2,
        // NOTE: 'červen' is substring of 'červenec'; therefore 'červenec' must precede 'červen' in the regex to be fully matched.
        // Otherwise parser matches '1. červenec' as '1. červen' + 'ec'.
        monthsStrictRegex:
            /^(leden|ledna|února|únor|březen|března|duben|dubna|květen|května|červenec|července|červen|června|srpen|srpna|září|říjen|října|listopadu|listopad|prosinec|prosince)/i,
        monthsShortStrictRegex:
            /^(led|úno|bře|dub|kvě|čvn|čvc|srp|zář|říj|lis|pro)/i,
        monthsParse: monthsParse$1,
        longMonthsParse: monthsParse$1,
        shortMonthsParse: monthsParse$1,
        weekdays: 'neděle_pondělí_úterý_středa_čtvrtek_pátek_sobota'.split('_'),
        weekdaysShort: 'ne_po_út_st_čt_pá_so'.split('_'),
        weekdaysMin: 'ne_po_út_st_čt_pá_so'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY H:mm',
            LLLL: 'dddd D. MMMM YYYY H:mm',
            l: 'D. M. YYYY',
        },
        calendar: {
            sameDay: '[dnes v] LT',
            nextDay: '[zítra v] LT',
            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[v neděli v] LT';
                    case 1:
                    case 2:
                        return '[v] dddd [v] LT';
                    case 3:
                        return '[ve středu v] LT';
                    case 4:
                        return '[ve čtvrtek v] LT';
                    case 5:
                        return '[v pátek v] LT';
                    case 6:
                        return '[v sobotu v] LT';
                }
            },
            lastDay: '[včera v] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[minulou neděli v] LT';
                    case 1:
                    case 2:
                        return '[minulé] dddd [v] LT';
                    case 3:
                        return '[minulou středu v] LT';
                    case 4:
                    case 5:
                        return '[minulý] dddd [v] LT';
                    case 6:
                        return '[minulou sobotu v] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'za %s',
            past: 'před %s',
            s: translate$1,
            ss: translate$1,
            m: translate$1,
            mm: translate$1,
            h: translate$1,
            hh: translate$1,
            d: translate$1,
            dd: translate$1,
            M: translate$1,
            MM: translate$1,
            y: translate$1,
            yy: translate$1,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('cv', {
        months: 'кӑрлач_нарӑс_пуш_ака_май_ҫӗртме_утӑ_ҫурла_авӑн_юпа_чӳк_раштав'.split(
            '_'
        ),
        monthsShort: 'кӑр_нар_пуш_ака_май_ҫӗр_утӑ_ҫур_авн_юпа_чӳк_раш'.split('_'),
        weekdays:
            'вырсарникун_тунтикун_ытларикун_юнкун_кӗҫнерникун_эрнекун_шӑматкун'.split(
                '_'
            ),
        weekdaysShort: 'выр_тун_ытл_юн_кӗҫ_эрн_шӑм'.split('_'),
        weekdaysMin: 'вр_тн_ыт_юн_кҫ_эр_шм'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD-MM-YYYY',
            LL: 'YYYY [ҫулхи] MMMM [уйӑхӗн] D[-мӗшӗ]',
            LLL: 'YYYY [ҫулхи] MMMM [уйӑхӗн] D[-мӗшӗ], HH:mm',
            LLLL: 'dddd, YYYY [ҫулхи] MMMM [уйӑхӗн] D[-мӗшӗ], HH:mm',
        },
        calendar: {
            sameDay: '[Паян] LT [сехетре]',
            nextDay: '[Ыран] LT [сехетре]',
            lastDay: '[Ӗнер] LT [сехетре]',
            nextWeek: '[Ҫитес] dddd LT [сехетре]',
            lastWeek: '[Иртнӗ] dddd LT [сехетре]',
            sameElse: 'L',
        },
        relativeTime: {
            future: function (output) {
                var affix = /сехет$/i.exec(output)
                    ? 'рен'
                    : /ҫул$/i.exec(output)
                    ? 'тан'
                    : 'ран';
                return output + affix;
            },
            past: '%s каялла',
            s: 'пӗр-ик ҫеккунт',
            ss: '%d ҫеккунт',
            m: 'пӗр минут',
            mm: '%d минут',
            h: 'пӗр сехет',
            hh: '%d сехет',
            d: 'пӗр кун',
            dd: '%d кун',
            M: 'пӗр уйӑх',
            MM: '%d уйӑх',
            y: 'пӗр ҫул',
            yy: '%d ҫул',
        },
        dayOfMonthOrdinalParse: /\d{1,2}-мӗш/,
        ordinal: '%d-мӗш',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('cy', {
        months: 'Ionawr_Chwefror_Mawrth_Ebrill_Mai_Mehefin_Gorffennaf_Awst_Medi_Hydref_Tachwedd_Rhagfyr'.split(
            '_'
        ),
        monthsShort: 'Ion_Chwe_Maw_Ebr_Mai_Meh_Gor_Aws_Med_Hyd_Tach_Rhag'.split(
            '_'
        ),
        weekdays:
            'Dydd Sul_Dydd Llun_Dydd Mawrth_Dydd Mercher_Dydd Iau_Dydd Gwener_Dydd Sadwrn'.split(
                '_'
            ),
        weekdaysShort: 'Sul_Llun_Maw_Mer_Iau_Gwe_Sad'.split('_'),
        weekdaysMin: 'Su_Ll_Ma_Me_Ia_Gw_Sa'.split('_'),
        weekdaysParseExact: true,
        // time formats are the same as en-gb
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Heddiw am] LT',
            nextDay: '[Yfory am] LT',
            nextWeek: 'dddd [am] LT',
            lastDay: '[Ddoe am] LT',
            lastWeek: 'dddd [diwethaf am] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'mewn %s',
            past: '%s yn ôl',
            s: 'ychydig eiliadau',
            ss: '%d eiliad',
            m: 'munud',
            mm: '%d munud',
            h: 'awr',
            hh: '%d awr',
            d: 'diwrnod',
            dd: '%d diwrnod',
            M: 'mis',
            MM: '%d mis',
            y: 'blwyddyn',
            yy: '%d flynedd',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(fed|ain|af|il|ydd|ed|eg)/,
        // traditional ordinal numbers above 31 are not commonly used in colloquial Welsh
        ordinal: function (number) {
            var b = number,
                output = '',
                lookup = [
                    '',
                    'af',
                    'il',
                    'ydd',
                    'ydd',
                    'ed',
                    'ed',
                    'ed',
                    'fed',
                    'fed',
                    'fed', // 1af to 10fed
                    'eg',
                    'fed',
                    'eg',
                    'eg',
                    'fed',
                    'eg',
                    'eg',
                    'fed',
                    'eg',
                    'fed', // 11eg to 20fed
                ];
            if (b > 20) {
                if (b === 40 || b === 50 || b === 60 || b === 80 || b === 100) {
                    output = 'fed'; // not 30ain, 70ain or 90ain
                } else {
                    output = 'ain';
                }
            } else if (b > 0) {
                output = lookup[b];
            }
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('da', {
        months: 'januar_februar_marts_april_maj_juni_juli_august_september_oktober_november_december'.split(
            '_'
        ),
        monthsShort: 'jan_feb_mar_apr_maj_jun_jul_aug_sep_okt_nov_dec'.split('_'),
        weekdays: 'søndag_mandag_tirsdag_onsdag_torsdag_fredag_lørdag'.split('_'),
        weekdaysShort: 'søn_man_tir_ons_tor_fre_lør'.split('_'),
        weekdaysMin: 'sø_ma_ti_on_to_fr_lø'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY HH:mm',
            LLLL: 'dddd [d.] D. MMMM YYYY [kl.] HH:mm',
        },
        calendar: {
            sameDay: '[i dag kl.] LT',
            nextDay: '[i morgen kl.] LT',
            nextWeek: 'på dddd [kl.] LT',
            lastDay: '[i går kl.] LT',
            lastWeek: '[i] dddd[s kl.] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'om %s',
            past: '%s siden',
            s: 'få sekunder',
            ss: '%d sekunder',
            m: 'et minut',
            mm: '%d minutter',
            h: 'en time',
            hh: '%d timer',
            d: 'en dag',
            dd: '%d dage',
            M: 'en måned',
            MM: '%d måneder',
            y: 'et år',
            yy: '%d år',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function processRelativeTime(number, withoutSuffix, key, isFuture) {
        var format = {
            m: ['eine Minute', 'einer Minute'],
            h: ['eine Stunde', 'einer Stunde'],
            d: ['ein Tag', 'einem Tag'],
            dd: [number + ' Tage', number + ' Tagen'],
            w: ['eine Woche', 'einer Woche'],
            M: ['ein Monat', 'einem Monat'],
            MM: [number + ' Monate', number + ' Monaten'],
            y: ['ein Jahr', 'einem Jahr'],
            yy: [number + ' Jahre', number + ' Jahren'],
        };
        return withoutSuffix ? format[key][0] : format[key][1];
    }

    hooks.defineLocale('de-at', {
        months: 'Jänner_Februar_März_April_Mai_Juni_Juli_August_September_Oktober_November_Dezember'.split(
            '_'
        ),
        monthsShort:
            'Jän._Feb._März_Apr._Mai_Juni_Juli_Aug._Sep._Okt._Nov._Dez.'.split('_'),
        monthsParseExact: true,
        weekdays:
            'Sonntag_Montag_Dienstag_Mittwoch_Donnerstag_Freitag_Samstag'.split(
                '_'
            ),
        weekdaysShort: 'So._Mo._Di._Mi._Do._Fr._Sa.'.split('_'),
        weekdaysMin: 'So_Mo_Di_Mi_Do_Fr_Sa'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY HH:mm',
            LLLL: 'dddd, D. MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[heute um] LT [Uhr]',
            sameElse: 'L',
            nextDay: '[morgen um] LT [Uhr]',
            nextWeek: 'dddd [um] LT [Uhr]',
            lastDay: '[gestern um] LT [Uhr]',
            lastWeek: '[letzten] dddd [um] LT [Uhr]',
        },
        relativeTime: {
            future: 'in %s',
            past: 'vor %s',
            s: 'ein paar Sekunden',
            ss: '%d Sekunden',
            m: processRelativeTime,
            mm: '%d Minuten',
            h: processRelativeTime,
            hh: '%d Stunden',
            d: processRelativeTime,
            dd: processRelativeTime,
            w: processRelativeTime,
            ww: '%d Wochen',
            M: processRelativeTime,
            MM: processRelativeTime,
            y: processRelativeTime,
            yy: processRelativeTime,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function processRelativeTime$1(number, withoutSuffix, key, isFuture) {
        var format = {
            m: ['eine Minute', 'einer Minute'],
            h: ['eine Stunde', 'einer Stunde'],
            d: ['ein Tag', 'einem Tag'],
            dd: [number + ' Tage', number + ' Tagen'],
            w: ['eine Woche', 'einer Woche'],
            M: ['ein Monat', 'einem Monat'],
            MM: [number + ' Monate', number + ' Monaten'],
            y: ['ein Jahr', 'einem Jahr'],
            yy: [number + ' Jahre', number + ' Jahren'],
        };
        return withoutSuffix ? format[key][0] : format[key][1];
    }

    hooks.defineLocale('de-ch', {
        months: 'Januar_Februar_März_April_Mai_Juni_Juli_August_September_Oktober_November_Dezember'.split(
            '_'
        ),
        monthsShort:
            'Jan._Feb._März_Apr._Mai_Juni_Juli_Aug._Sep._Okt._Nov._Dez.'.split('_'),
        monthsParseExact: true,
        weekdays:
            'Sonntag_Montag_Dienstag_Mittwoch_Donnerstag_Freitag_Samstag'.split(
                '_'
            ),
        weekdaysShort: 'So_Mo_Di_Mi_Do_Fr_Sa'.split('_'),
        weekdaysMin: 'So_Mo_Di_Mi_Do_Fr_Sa'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY HH:mm',
            LLLL: 'dddd, D. MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[heute um] LT [Uhr]',
            sameElse: 'L',
            nextDay: '[morgen um] LT [Uhr]',
            nextWeek: 'dddd [um] LT [Uhr]',
            lastDay: '[gestern um] LT [Uhr]',
            lastWeek: '[letzten] dddd [um] LT [Uhr]',
        },
        relativeTime: {
            future: 'in %s',
            past: 'vor %s',
            s: 'ein paar Sekunden',
            ss: '%d Sekunden',
            m: processRelativeTime$1,
            mm: '%d Minuten',
            h: processRelativeTime$1,
            hh: '%d Stunden',
            d: processRelativeTime$1,
            dd: processRelativeTime$1,
            w: processRelativeTime$1,
            ww: '%d Wochen',
            M: processRelativeTime$1,
            MM: processRelativeTime$1,
            y: processRelativeTime$1,
            yy: processRelativeTime$1,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function processRelativeTime$2(number, withoutSuffix, key, isFuture) {
        var format = {
            m: ['eine Minute', 'einer Minute'],
            h: ['eine Stunde', 'einer Stunde'],
            d: ['ein Tag', 'einem Tag'],
            dd: [number + ' Tage', number + ' Tagen'],
            w: ['eine Woche', 'einer Woche'],
            M: ['ein Monat', 'einem Monat'],
            MM: [number + ' Monate', number + ' Monaten'],
            y: ['ein Jahr', 'einem Jahr'],
            yy: [number + ' Jahre', number + ' Jahren'],
        };
        return withoutSuffix ? format[key][0] : format[key][1];
    }

    hooks.defineLocale('de', {
        months: 'Januar_Februar_März_April_Mai_Juni_Juli_August_September_Oktober_November_Dezember'.split(
            '_'
        ),
        monthsShort:
            'Jan._Feb._März_Apr._Mai_Juni_Juli_Aug._Sep._Okt._Nov._Dez.'.split('_'),
        monthsParseExact: true,
        weekdays:
            'Sonntag_Montag_Dienstag_Mittwoch_Donnerstag_Freitag_Samstag'.split(
                '_'
            ),
        weekdaysShort: 'So._Mo._Di._Mi._Do._Fr._Sa.'.split('_'),
        weekdaysMin: 'So_Mo_Di_Mi_Do_Fr_Sa'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY HH:mm',
            LLLL: 'dddd, D. MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[heute um] LT [Uhr]',
            sameElse: 'L',
            nextDay: '[morgen um] LT [Uhr]',
            nextWeek: 'dddd [um] LT [Uhr]',
            lastDay: '[gestern um] LT [Uhr]',
            lastWeek: '[letzten] dddd [um] LT [Uhr]',
        },
        relativeTime: {
            future: 'in %s',
            past: 'vor %s',
            s: 'ein paar Sekunden',
            ss: '%d Sekunden',
            m: processRelativeTime$2,
            mm: '%d Minuten',
            h: processRelativeTime$2,
            hh: '%d Stunden',
            d: processRelativeTime$2,
            dd: processRelativeTime$2,
            w: processRelativeTime$2,
            ww: '%d Wochen',
            M: processRelativeTime$2,
            MM: processRelativeTime$2,
            y: processRelativeTime$2,
            yy: processRelativeTime$2,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var months$5 = [
            'ޖެނުއަރީ',
            'ފެބްރުއަރީ',
            'މާރިޗު',
            'އޭޕްރީލު',
            'މޭ',
            'ޖޫން',
            'ޖުލައި',
            'އޯގަސްޓު',
            'ސެޕްޓެމްބަރު',
            'އޮކްޓޯބަރު',
            'ނޮވެމްބަރު',
            'ޑިސެމްބަރު',
        ],
        weekdays = [
            'އާދިއްތަ',
            'ހޯމަ',
            'އަންގާރަ',
            'ބުދަ',
            'ބުރާސްފަތި',
            'ހުކުރު',
            'ހޮނިހިރު',
        ];

    hooks.defineLocale('dv', {
        months: months$5,
        monthsShort: months$5,
        weekdays: weekdays,
        weekdaysShort: weekdays,
        weekdaysMin: 'އާދި_ހޯމަ_އަން_ބުދަ_ބުރާ_ހުކު_ހޮނި'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'D/M/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        meridiemParse: /މކ|މފ/,
        isPM: function (input) {
            return 'މފ' === input;
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'މކ';
            } else {
                return 'މފ';
            }
        },
        calendar: {
            sameDay: '[މިއަދު] LT',
            nextDay: '[މާދަމާ] LT',
            nextWeek: 'dddd LT',
            lastDay: '[އިއްޔެ] LT',
            lastWeek: '[ފާއިތުވި] dddd LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'ތެރޭގައި %s',
            past: 'ކުރިން %s',
            s: 'ސިކުންތުކޮޅެއް',
            ss: 'd% ސިކުންތު',
            m: 'މިނިޓެއް',
            mm: 'މިނިޓު %d',
            h: 'ގަޑިއިރެއް',
            hh: 'ގަޑިއިރު %d',
            d: 'ދުވަހެއް',
            dd: 'ދުވަސް %d',
            M: 'މަހެއް',
            MM: 'މަސް %d',
            y: 'އަހަރެއް',
            yy: 'އަހަރު %d',
        },
        preparse: function (string) {
            return string.replace(/،/g, ',');
        },
        postformat: function (string) {
            return string.replace(/,/g, '،');
        },
        week: {
            dow: 7, // Sunday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function isFunction$1(input) {
        return (
            (typeof Function !== 'undefined' && input instanceof Function) ||
            Object.prototype.toString.call(input) === '[object Function]'
        );
    }

    hooks.defineLocale('el', {
        monthsNominativeEl:
            'Ιανουάριος_Φεβρουάριος_Μάρτιος_Απρίλιος_Μάιος_Ιούνιος_Ιούλιος_Αύγουστος_Σεπτέμβριος_Οκτώβριος_Νοέμβριος_Δεκέμβριος'.split(
                '_'
            ),
        monthsGenitiveEl:
            'Ιανουαρίου_Φεβρουαρίου_Μαρτίου_Απριλίου_Μαΐου_Ιουνίου_Ιουλίου_Αυγούστου_Σεπτεμβρίου_Οκτωβρίου_Νοεμβρίου_Δεκεμβρίου'.split(
                '_'
            ),
        months: function (momentToFormat, format) {
            if (!momentToFormat) {
                return this._monthsNominativeEl;
            } else if (
                typeof format === 'string' &&
                /D/.test(format.substring(0, format.indexOf('MMMM')))
            ) {
                // if there is a day number before 'MMMM'
                return this._monthsGenitiveEl[momentToFormat.month()];
            } else {
                return this._monthsNominativeEl[momentToFormat.month()];
            }
        },
        monthsShort: 'Ιαν_Φεβ_Μαρ_Απρ_Μαϊ_Ιουν_Ιουλ_Αυγ_Σεπ_Οκτ_Νοε_Δεκ'.split('_'),
        weekdays: 'Κυριακή_Δευτέρα_Τρίτη_Τετάρτη_Πέμπτη_Παρασκευή_Σάββατο'.split(
            '_'
        ),
        weekdaysShort: 'Κυρ_Δευ_Τρι_Τετ_Πεμ_Παρ_Σαβ'.split('_'),
        weekdaysMin: 'Κυ_Δε_Τρ_Τε_Πε_Πα_Σα'.split('_'),
        meridiem: function (hours, minutes, isLower) {
            if (hours > 11) {
                return isLower ? 'μμ' : 'ΜΜ';
            } else {
                return isLower ? 'πμ' : 'ΠΜ';
            }
        },
        isPM: function (input) {
            return (input + '').toLowerCase()[0] === 'μ';
        },
        meridiemParse: /[ΠΜ]\.?Μ?\.?/i,
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY h:mm A',
            LLLL: 'dddd, D MMMM YYYY h:mm A',
        },
        calendarEl: {
            sameDay: '[Σήμερα {}] LT',
            nextDay: '[Αύριο {}] LT',
            nextWeek: 'dddd [{}] LT',
            lastDay: '[Χθες {}] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 6:
                        return '[το προηγούμενο] dddd [{}] LT';
                    default:
                        return '[την προηγούμενη] dddd [{}] LT';
                }
            },
            sameElse: 'L',
        },
        calendar: function (key, mom) {
            var output = this._calendarEl[key],
                hours = mom && mom.hours();
            if (isFunction$1(output)) {
                output = output.apply(mom);
            }
            return output.replace('{}', hours % 12 === 1 ? 'στη' : 'στις');
        },
        relativeTime: {
            future: 'σε %s',
            past: '%s πριν',
            s: 'λίγα δευτερόλεπτα',
            ss: '%d δευτερόλεπτα',
            m: 'ένα λεπτό',
            mm: '%d λεπτά',
            h: 'μία ώρα',
            hh: '%d ώρες',
            d: 'μία μέρα',
            dd: '%d μέρες',
            M: 'ένας μήνας',
            MM: '%d μήνες',
            y: 'ένας χρόνος',
            yy: '%d χρόνια',
        },
        dayOfMonthOrdinalParse: /\d{1,2}η/,
        ordinal: '%dη',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4st is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-au', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY h:mm A',
            LLLL: 'dddd, D MMMM YYYY h:mm A',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-ca', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'YYYY-MM-DD',
            LL: 'MMMM D, YYYY',
            LLL: 'MMMM D, YYYY h:mm A',
            LLLL: 'dddd, MMMM D, YYYY h:mm A',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-gb', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-ie', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-il', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-in', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY h:mm A',
            LLLL: 'dddd, D MMMM YYYY h:mm A',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 1st is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-nz', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY h:mm A',
            LLLL: 'dddd, D MMMM YYYY h:mm A',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('en-sg', {
        months: 'January_February_March_April_May_June_July_August_September_October_November_December'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        weekdays: 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split(
            '_'
        ),
        weekdaysShort: 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysMin: 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Today at] LT',
            nextDay: '[Tomorrow at] LT',
            nextWeek: 'dddd [at] LT',
            lastDay: '[Yesterday at] LT',
            lastWeek: '[Last] dddd [at] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'in %s',
            past: '%s ago',
            s: 'a few seconds',
            ss: '%d seconds',
            m: 'a minute',
            mm: '%d minutes',
            h: 'an hour',
            hh: '%d hours',
            d: 'a day',
            dd: '%d days',
            M: 'a month',
            MM: '%d months',
            y: 'a year',
            yy: '%d years',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('eo', {
        months: 'januaro_februaro_marto_aprilo_majo_junio_julio_aŭgusto_septembro_oktobro_novembro_decembro'.split(
            '_'
        ),
        monthsShort: 'jan_feb_mart_apr_maj_jun_jul_aŭg_sept_okt_nov_dec'.split('_'),
        weekdays: 'dimanĉo_lundo_mardo_merkredo_ĵaŭdo_vendredo_sabato'.split('_'),
        weekdaysShort: 'dim_lun_mard_merk_ĵaŭ_ven_sab'.split('_'),
        weekdaysMin: 'di_lu_ma_me_ĵa_ve_sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY-MM-DD',
            LL: '[la] D[-an de] MMMM, YYYY',
            LLL: '[la] D[-an de] MMMM, YYYY HH:mm',
            LLLL: 'dddd[n], [la] D[-an de] MMMM, YYYY HH:mm',
            llll: 'ddd, [la] D[-an de] MMM, YYYY HH:mm',
        },
        meridiemParse: /[ap]\.t\.m/i,
        isPM: function (input) {
            return input.charAt(0).toLowerCase() === 'p';
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours > 11) {
                return isLower ? 'p.t.m.' : 'P.T.M.';
            } else {
                return isLower ? 'a.t.m.' : 'A.T.M.';
            }
        },
        calendar: {
            sameDay: '[Hodiaŭ je] LT',
            nextDay: '[Morgaŭ je] LT',
            nextWeek: 'dddd[n je] LT',
            lastDay: '[Hieraŭ je] LT',
            lastWeek: '[pasintan] dddd[n je] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'post %s',
            past: 'antaŭ %s',
            s: 'kelkaj sekundoj',
            ss: '%d sekundoj',
            m: 'unu minuto',
            mm: '%d minutoj',
            h: 'unu horo',
            hh: '%d horoj',
            d: 'unu tago', //ne 'diurno', ĉar estas uzita por proksimumo
            dd: '%d tagoj',
            M: 'unu monato',
            MM: '%d monatoj',
            y: 'unu jaro',
            yy: '%d jaroj',
        },
        dayOfMonthOrdinalParse: /\d{1,2}a/,
        ordinal: '%da',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsShortDot =
            'ene._feb._mar._abr._may._jun._jul._ago._sep._oct._nov._dic.'.split(
                '_'
            ),
        monthsShort$1 = 'ene_feb_mar_abr_may_jun_jul_ago_sep_oct_nov_dic'.split('_'),
        monthsParse$2 = [
            /^ene/i,
            /^feb/i,
            /^mar/i,
            /^abr/i,
            /^may/i,
            /^jun/i,
            /^jul/i,
            /^ago/i,
            /^sep/i,
            /^oct/i,
            /^nov/i,
            /^dic/i,
        ],
        monthsRegex$3 =
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i;

    hooks.defineLocale('es-do', {
        months: 'enero_febrero_marzo_abril_mayo_junio_julio_agosto_septiembre_octubre_noviembre_diciembre'.split(
            '_'
        ),
        monthsShort: function (m, format) {
            if (!m) {
                return monthsShortDot;
            } else if (/-MMM-/.test(format)) {
                return monthsShort$1[m.month()];
            } else {
                return monthsShortDot[m.month()];
            }
        },
        monthsRegex: monthsRegex$3,
        monthsShortRegex: monthsRegex$3,
        monthsStrictRegex:
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)/i,
        monthsShortStrictRegex:
            /^(ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i,
        monthsParse: monthsParse$2,
        longMonthsParse: monthsParse$2,
        shortMonthsParse: monthsParse$2,
        weekdays: 'domingo_lunes_martes_miércoles_jueves_viernes_sábado'.split('_'),
        weekdaysShort: 'dom._lun._mar._mié._jue._vie._sáb.'.split('_'),
        weekdaysMin: 'do_lu_ma_mi_ju_vi_sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'DD/MM/YYYY',
            LL: 'D [de] MMMM [de] YYYY',
            LLL: 'D [de] MMMM [de] YYYY h:mm A',
            LLLL: 'dddd, D [de] MMMM [de] YYYY h:mm A',
        },
        calendar: {
            sameDay: function () {
                return '[hoy a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextDay: function () {
                return '[mañana a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextWeek: function () {
                return 'dddd [a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastDay: function () {
                return '[ayer a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastWeek: function () {
                return (
                    '[el] dddd [pasado a la' +
                    (this.hours() !== 1 ? 's' : '') +
                    '] LT'
                );
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'en %s',
            past: 'hace %s',
            s: 'unos segundos',
            ss: '%d segundos',
            m: 'un minuto',
            mm: '%d minutos',
            h: 'una hora',
            hh: '%d horas',
            d: 'un día',
            dd: '%d días',
            w: 'una semana',
            ww: '%d semanas',
            M: 'un mes',
            MM: '%d meses',
            y: 'un año',
            yy: '%d años',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsShortDot$1 =
            'ene._feb._mar._abr._may._jun._jul._ago._sep._oct._nov._dic.'.split(
                '_'
            ),
        monthsShort$2 = 'ene_feb_mar_abr_may_jun_jul_ago_sep_oct_nov_dic'.split('_'),
        monthsParse$3 = [
            /^ene/i,
            /^feb/i,
            /^mar/i,
            /^abr/i,
            /^may/i,
            /^jun/i,
            /^jul/i,
            /^ago/i,
            /^sep/i,
            /^oct/i,
            /^nov/i,
            /^dic/i,
        ],
        monthsRegex$4 =
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i;

    hooks.defineLocale('es-mx', {
        months: 'enero_febrero_marzo_abril_mayo_junio_julio_agosto_septiembre_octubre_noviembre_diciembre'.split(
            '_'
        ),
        monthsShort: function (m, format) {
            if (!m) {
                return monthsShortDot$1;
            } else if (/-MMM-/.test(format)) {
                return monthsShort$2[m.month()];
            } else {
                return monthsShortDot$1[m.month()];
            }
        },
        monthsRegex: monthsRegex$4,
        monthsShortRegex: monthsRegex$4,
        monthsStrictRegex:
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)/i,
        monthsShortStrictRegex:
            /^(ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i,
        monthsParse: monthsParse$3,
        longMonthsParse: monthsParse$3,
        shortMonthsParse: monthsParse$3,
        weekdays: 'domingo_lunes_martes_miércoles_jueves_viernes_sábado'.split('_'),
        weekdaysShort: 'dom._lun._mar._mié._jue._vie._sáb.'.split('_'),
        weekdaysMin: 'do_lu_ma_mi_ju_vi_sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D [de] MMMM [de] YYYY',
            LLL: 'D [de] MMMM [de] YYYY H:mm',
            LLLL: 'dddd, D [de] MMMM [de] YYYY H:mm',
        },
        calendar: {
            sameDay: function () {
                return '[hoy a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextDay: function () {
                return '[mañana a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextWeek: function () {
                return 'dddd [a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastDay: function () {
                return '[ayer a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastWeek: function () {
                return (
                    '[el] dddd [pasado a la' +
                    (this.hours() !== 1 ? 's' : '') +
                    '] LT'
                );
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'en %s',
            past: 'hace %s',
            s: 'unos segundos',
            ss: '%d segundos',
            m: 'un minuto',
            mm: '%d minutos',
            h: 'una hora',
            hh: '%d horas',
            d: 'un día',
            dd: '%d días',
            w: 'una semana',
            ww: '%d semanas',
            M: 'un mes',
            MM: '%d meses',
            y: 'un año',
            yy: '%d años',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
        invalidDate: 'Fecha inválida',
    });

    //! moment.js locale configuration

    var monthsShortDot$2 =
            'ene._feb._mar._abr._may._jun._jul._ago._sep._oct._nov._dic.'.split(
                '_'
            ),
        monthsShort$3 = 'ene_feb_mar_abr_may_jun_jul_ago_sep_oct_nov_dic'.split('_'),
        monthsParse$4 = [
            /^ene/i,
            /^feb/i,
            /^mar/i,
            /^abr/i,
            /^may/i,
            /^jun/i,
            /^jul/i,
            /^ago/i,
            /^sep/i,
            /^oct/i,
            /^nov/i,
            /^dic/i,
        ],
        monthsRegex$5 =
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i;

    hooks.defineLocale('es-us', {
        months: 'enero_febrero_marzo_abril_mayo_junio_julio_agosto_septiembre_octubre_noviembre_diciembre'.split(
            '_'
        ),
        monthsShort: function (m, format) {
            if (!m) {
                return monthsShortDot$2;
            } else if (/-MMM-/.test(format)) {
                return monthsShort$3[m.month()];
            } else {
                return monthsShortDot$2[m.month()];
            }
        },
        monthsRegex: monthsRegex$5,
        monthsShortRegex: monthsRegex$5,
        monthsStrictRegex:
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)/i,
        monthsShortStrictRegex:
            /^(ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i,
        monthsParse: monthsParse$4,
        longMonthsParse: monthsParse$4,
        shortMonthsParse: monthsParse$4,
        weekdays: 'domingo_lunes_martes_miércoles_jueves_viernes_sábado'.split('_'),
        weekdaysShort: 'dom._lun._mar._mié._jue._vie._sáb.'.split('_'),
        weekdaysMin: 'do_lu_ma_mi_ju_vi_sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'MM/DD/YYYY',
            LL: 'D [de] MMMM [de] YYYY',
            LLL: 'D [de] MMMM [de] YYYY h:mm A',
            LLLL: 'dddd, D [de] MMMM [de] YYYY h:mm A',
        },
        calendar: {
            sameDay: function () {
                return '[hoy a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextDay: function () {
                return '[mañana a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextWeek: function () {
                return 'dddd [a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastDay: function () {
                return '[ayer a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastWeek: function () {
                return (
                    '[el] dddd [pasado a la' +
                    (this.hours() !== 1 ? 's' : '') +
                    '] LT'
                );
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'en %s',
            past: 'hace %s',
            s: 'unos segundos',
            ss: '%d segundos',
            m: 'un minuto',
            mm: '%d minutos',
            h: 'una hora',
            hh: '%d horas',
            d: 'un día',
            dd: '%d días',
            w: 'una semana',
            ww: '%d semanas',
            M: 'un mes',
            MM: '%d meses',
            y: 'un año',
            yy: '%d años',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsShortDot$3 =
            'ene._feb._mar._abr._may._jun._jul._ago._sep._oct._nov._dic.'.split(
                '_'
            ),
        monthsShort$4 = 'ene_feb_mar_abr_may_jun_jul_ago_sep_oct_nov_dic'.split('_'),
        monthsParse$5 = [
            /^ene/i,
            /^feb/i,
            /^mar/i,
            /^abr/i,
            /^may/i,
            /^jun/i,
            /^jul/i,
            /^ago/i,
            /^sep/i,
            /^oct/i,
            /^nov/i,
            /^dic/i,
        ],
        monthsRegex$6 =
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i;

    hooks.defineLocale('es', {
        months: 'enero_febrero_marzo_abril_mayo_junio_julio_agosto_septiembre_octubre_noviembre_diciembre'.split(
            '_'
        ),
        monthsShort: function (m, format) {
            if (!m) {
                return monthsShortDot$3;
            } else if (/-MMM-/.test(format)) {
                return monthsShort$4[m.month()];
            } else {
                return monthsShortDot$3[m.month()];
            }
        },
        monthsRegex: monthsRegex$6,
        monthsShortRegex: monthsRegex$6,
        monthsStrictRegex:
            /^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)/i,
        monthsShortStrictRegex:
            /^(ene\.?|feb\.?|mar\.?|abr\.?|may\.?|jun\.?|jul\.?|ago\.?|sep\.?|oct\.?|nov\.?|dic\.?)/i,
        monthsParse: monthsParse$5,
        longMonthsParse: monthsParse$5,
        shortMonthsParse: monthsParse$5,
        weekdays: 'domingo_lunes_martes_miércoles_jueves_viernes_sábado'.split('_'),
        weekdaysShort: 'dom._lun._mar._mié._jue._vie._sáb.'.split('_'),
        weekdaysMin: 'do_lu_ma_mi_ju_vi_sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D [de] MMMM [de] YYYY',
            LLL: 'D [de] MMMM [de] YYYY H:mm',
            LLLL: 'dddd, D [de] MMMM [de] YYYY H:mm',
        },
        calendar: {
            sameDay: function () {
                return '[hoy a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextDay: function () {
                return '[mañana a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            nextWeek: function () {
                return 'dddd [a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastDay: function () {
                return '[ayer a la' + (this.hours() !== 1 ? 's' : '') + '] LT';
            },
            lastWeek: function () {
                return (
                    '[el] dddd [pasado a la' +
                    (this.hours() !== 1 ? 's' : '') +
                    '] LT'
                );
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'en %s',
            past: 'hace %s',
            s: 'unos segundos',
            ss: '%d segundos',
            m: 'un minuto',
            mm: '%d minutos',
            h: 'una hora',
            hh: '%d horas',
            d: 'un día',
            dd: '%d días',
            w: 'una semana',
            ww: '%d semanas',
            M: 'un mes',
            MM: '%d meses',
            y: 'un año',
            yy: '%d años',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
        invalidDate: 'Fecha inválida',
    });

    //! moment.js locale configuration

    function processRelativeTime$3(number, withoutSuffix, key, isFuture) {
        var format = {
            s: ['mõne sekundi', 'mõni sekund', 'paar sekundit'],
            ss: [number + 'sekundi', number + 'sekundit'],
            m: ['ühe minuti', 'üks minut'],
            mm: [number + ' minuti', number + ' minutit'],
            h: ['ühe tunni', 'tund aega', 'üks tund'],
            hh: [number + ' tunni', number + ' tundi'],
            d: ['ühe päeva', 'üks päev'],
            M: ['kuu aja', 'kuu aega', 'üks kuu'],
            MM: [number + ' kuu', number + ' kuud'],
            y: ['ühe aasta', 'aasta', 'üks aasta'],
            yy: [number + ' aasta', number + ' aastat'],
        };
        if (withoutSuffix) {
            return format[key][2] ? format[key][2] : format[key][1];
        }
        return isFuture ? format[key][0] : format[key][1];
    }

    hooks.defineLocale('et', {
        months: 'jaanuar_veebruar_märts_aprill_mai_juuni_juuli_august_september_oktoober_november_detsember'.split(
            '_'
        ),
        monthsShort:
            'jaan_veebr_märts_apr_mai_juuni_juuli_aug_sept_okt_nov_dets'.split('_'),
        weekdays:
            'pühapäev_esmaspäev_teisipäev_kolmapäev_neljapäev_reede_laupäev'.split(
                '_'
            ),
        weekdaysShort: 'P_E_T_K_N_R_L'.split('_'),
        weekdaysMin: 'P_E_T_K_N_R_L'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY H:mm',
            LLLL: 'dddd, D. MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[Täna,] LT',
            nextDay: '[Homme,] LT',
            nextWeek: '[Järgmine] dddd LT',
            lastDay: '[Eile,] LT',
            lastWeek: '[Eelmine] dddd LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s pärast',
            past: '%s tagasi',
            s: processRelativeTime$3,
            ss: processRelativeTime$3,
            m: processRelativeTime$3,
            mm: processRelativeTime$3,
            h: processRelativeTime$3,
            hh: processRelativeTime$3,
            d: processRelativeTime$3,
            dd: '%d päeva',
            M: processRelativeTime$3,
            MM: processRelativeTime$3,
            y: processRelativeTime$3,
            yy: processRelativeTime$3,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('eu', {
        months: 'urtarrila_otsaila_martxoa_apirila_maiatza_ekaina_uztaila_abuztua_iraila_urria_azaroa_abendua'.split(
            '_'
        ),
        monthsShort:
            'urt._ots._mar._api._mai._eka._uzt._abu._ira._urr._aza._abe.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays:
            'igandea_astelehena_asteartea_asteazkena_osteguna_ostirala_larunbata'.split(
                '_'
            ),
        weekdaysShort: 'ig._al._ar._az._og._ol._lr.'.split('_'),
        weekdaysMin: 'ig_al_ar_az_og_ol_lr'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY-MM-DD',
            LL: 'YYYY[ko] MMMM[ren] D[a]',
            LLL: 'YYYY[ko] MMMM[ren] D[a] HH:mm',
            LLLL: 'dddd, YYYY[ko] MMMM[ren] D[a] HH:mm',
            l: 'YYYY-M-D',
            ll: 'YYYY[ko] MMM D[a]',
            lll: 'YYYY[ko] MMM D[a] HH:mm',
            llll: 'ddd, YYYY[ko] MMM D[a] HH:mm',
        },
        calendar: {
            sameDay: '[gaur] LT[etan]',
            nextDay: '[bihar] LT[etan]',
            nextWeek: 'dddd LT[etan]',
            lastDay: '[atzo] LT[etan]',
            lastWeek: '[aurreko] dddd LT[etan]',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s barru',
            past: 'duela %s',
            s: 'segundo batzuk',
            ss: '%d segundo',
            m: 'minutu bat',
            mm: '%d minutu',
            h: 'ordu bat',
            hh: '%d ordu',
            d: 'egun bat',
            dd: '%d egun',
            M: 'hilabete bat',
            MM: '%d hilabete',
            y: 'urte bat',
            yy: '%d urte',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$6 = {
            1: '۱',
            2: '۲',
            3: '۳',
            4: '۴',
            5: '۵',
            6: '۶',
            7: '۷',
            8: '۸',
            9: '۹',
            0: '۰',
        },
        numberMap$5 = {
            '۱': '1',
            '۲': '2',
            '۳': '3',
            '۴': '4',
            '۵': '5',
            '۶': '6',
            '۷': '7',
            '۸': '8',
            '۹': '9',
            '۰': '0',
        };

    hooks.defineLocale('fa', {
        months: 'ژانویه_فوریه_مارس_آوریل_مه_ژوئن_ژوئیه_اوت_سپتامبر_اکتبر_نوامبر_دسامبر'.split(
            '_'
        ),
        monthsShort:
            'ژانویه_فوریه_مارس_آوریل_مه_ژوئن_ژوئیه_اوت_سپتامبر_اکتبر_نوامبر_دسامبر'.split(
                '_'
            ),
        weekdays:
            'یک\u200cشنبه_دوشنبه_سه\u200cشنبه_چهارشنبه_پنج\u200cشنبه_جمعه_شنبه'.split(
                '_'
            ),
        weekdaysShort:
            'یک\u200cشنبه_دوشنبه_سه\u200cشنبه_چهارشنبه_پنج\u200cشنبه_جمعه_شنبه'.split(
                '_'
            ),
        weekdaysMin: 'ی_د_س_چ_پ_ج_ش'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        meridiemParse: /قبل از ظهر|بعد از ظهر/,
        isPM: function (input) {
            return /بعد از ظهر/.test(input);
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'قبل از ظهر';
            } else {
                return 'بعد از ظهر';
            }
        },
        calendar: {
            sameDay: '[امروز ساعت] LT',
            nextDay: '[فردا ساعت] LT',
            nextWeek: 'dddd [ساعت] LT',
            lastDay: '[دیروز ساعت] LT',
            lastWeek: 'dddd [پیش] [ساعت] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'در %s',
            past: '%s پیش',
            s: 'چند ثانیه',
            ss: '%d ثانیه',
            m: 'یک دقیقه',
            mm: '%d دقیقه',
            h: 'یک ساعت',
            hh: '%d ساعت',
            d: 'یک روز',
            dd: '%d روز',
            M: 'یک ماه',
            MM: '%d ماه',
            y: 'یک سال',
            yy: '%d سال',
        },
        preparse: function (string) {
            return string
                .replace(/[۰-۹]/g, function (match) {
                    return numberMap$5[match];
                })
                .replace(/،/g, ',');
        },
        postformat: function (string) {
            return string
                .replace(/\d/g, function (match) {
                    return symbolMap$6[match];
                })
                .replace(/,/g, '،');
        },
        dayOfMonthOrdinalParse: /\d{1,2}م/,
        ordinal: '%dم',
        week: {
            dow: 6, // Saturday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var numbersPast =
            'nolla yksi kaksi kolme neljä viisi kuusi seitsemän kahdeksan yhdeksän'.split(
                ' '
            ),
        numbersFuture = [
            'nolla',
            'yhden',
            'kahden',
            'kolmen',
            'neljän',
            'viiden',
            'kuuden',
            numbersPast[7],
            numbersPast[8],
            numbersPast[9],
        ];
    function translate$2(number, withoutSuffix, key, isFuture) {
        var result = '';
        switch (key) {
            case 's':
                return isFuture ? 'muutaman sekunnin' : 'muutama sekunti';
            case 'ss':
                result = isFuture ? 'sekunnin' : 'sekuntia';
                break;
            case 'm':
                return isFuture ? 'minuutin' : 'minuutti';
            case 'mm':
                result = isFuture ? 'minuutin' : 'minuuttia';
                break;
            case 'h':
                return isFuture ? 'tunnin' : 'tunti';
            case 'hh':
                result = isFuture ? 'tunnin' : 'tuntia';
                break;
            case 'd':
                return isFuture ? 'päivän' : 'päivä';
            case 'dd':
                result = isFuture ? 'päivän' : 'päivää';
                break;
            case 'M':
                return isFuture ? 'kuukauden' : 'kuukausi';
            case 'MM':
                result = isFuture ? 'kuukauden' : 'kuukautta';
                break;
            case 'y':
                return isFuture ? 'vuoden' : 'vuosi';
            case 'yy':
                result = isFuture ? 'vuoden' : 'vuotta';
                break;
        }
        result = verbalNumber(number, isFuture) + ' ' + result;
        return result;
    }
    function verbalNumber(number, isFuture) {
        return number < 10
            ? isFuture
                ? numbersFuture[number]
                : numbersPast[number]
            : number;
    }

    hooks.defineLocale('fi', {
        months: 'tammikuu_helmikuu_maaliskuu_huhtikuu_toukokuu_kesäkuu_heinäkuu_elokuu_syyskuu_lokakuu_marraskuu_joulukuu'.split(
            '_'
        ),
        monthsShort:
            'tammi_helmi_maalis_huhti_touko_kesä_heinä_elo_syys_loka_marras_joulu'.split(
                '_'
            ),
        weekdays:
            'sunnuntai_maanantai_tiistai_keskiviikko_torstai_perjantai_lauantai'.split(
                '_'
            ),
        weekdaysShort: 'su_ma_ti_ke_to_pe_la'.split('_'),
        weekdaysMin: 'su_ma_ti_ke_to_pe_la'.split('_'),
        longDateFormat: {
            LT: 'HH.mm',
            LTS: 'HH.mm.ss',
            L: 'DD.MM.YYYY',
            LL: 'Do MMMM[ta] YYYY',
            LLL: 'Do MMMM[ta] YYYY, [klo] HH.mm',
            LLLL: 'dddd, Do MMMM[ta] YYYY, [klo] HH.mm',
            l: 'D.M.YYYY',
            ll: 'Do MMM YYYY',
            lll: 'Do MMM YYYY, [klo] HH.mm',
            llll: 'ddd, Do MMM YYYY, [klo] HH.mm',
        },
        calendar: {
            sameDay: '[tänään] [klo] LT',
            nextDay: '[huomenna] [klo] LT',
            nextWeek: 'dddd [klo] LT',
            lastDay: '[eilen] [klo] LT',
            lastWeek: '[viime] dddd[na] [klo] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s päästä',
            past: '%s sitten',
            s: translate$2,
            ss: translate$2,
            m: translate$2,
            mm: translate$2,
            h: translate$2,
            hh: translate$2,
            d: translate$2,
            dd: translate$2,
            M: translate$2,
            MM: translate$2,
            y: translate$2,
            yy: translate$2,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('fil', {
        months: 'Enero_Pebrero_Marso_Abril_Mayo_Hunyo_Hulyo_Agosto_Setyembre_Oktubre_Nobyembre_Disyembre'.split(
            '_'
        ),
        monthsShort: 'Ene_Peb_Mar_Abr_May_Hun_Hul_Ago_Set_Okt_Nob_Dis'.split('_'),
        weekdays: 'Linggo_Lunes_Martes_Miyerkules_Huwebes_Biyernes_Sabado'.split(
            '_'
        ),
        weekdaysShort: 'Lin_Lun_Mar_Miy_Huw_Biy_Sab'.split('_'),
        weekdaysMin: 'Li_Lu_Ma_Mi_Hu_Bi_Sab'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'MM/D/YYYY',
            LL: 'MMMM D, YYYY',
            LLL: 'MMMM D, YYYY HH:mm',
            LLLL: 'dddd, MMMM DD, YYYY HH:mm',
        },
        calendar: {
            sameDay: 'LT [ngayong araw]',
            nextDay: '[Bukas ng] LT',
            nextWeek: 'LT [sa susunod na] dddd',
            lastDay: 'LT [kahapon]',
            lastWeek: 'LT [noong nakaraang] dddd',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'sa loob ng %s',
            past: '%s ang nakalipas',
            s: 'ilang segundo',
            ss: '%d segundo',
            m: 'isang minuto',
            mm: '%d minuto',
            h: 'isang oras',
            hh: '%d oras',
            d: 'isang araw',
            dd: '%d araw',
            M: 'isang buwan',
            MM: '%d buwan',
            y: 'isang taon',
            yy: '%d taon',
        },
        dayOfMonthOrdinalParse: /\d{1,2}/,
        ordinal: function (number) {
            return number;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('fo', {
        months: 'januar_februar_mars_apríl_mai_juni_juli_august_september_oktober_november_desember'.split(
            '_'
        ),
        monthsShort: 'jan_feb_mar_apr_mai_jun_jul_aug_sep_okt_nov_des'.split('_'),
        weekdays:
            'sunnudagur_mánadagur_týsdagur_mikudagur_hósdagur_fríggjadagur_leygardagur'.split(
                '_'
            ),
        weekdaysShort: 'sun_mán_týs_mik_hós_frí_ley'.split('_'),
        weekdaysMin: 'su_má_tý_mi_hó_fr_le'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D. MMMM, YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Í dag kl.] LT',
            nextDay: '[Í morgin kl.] LT',
            nextWeek: 'dddd [kl.] LT',
            lastDay: '[Í gjár kl.] LT',
            lastWeek: '[síðstu] dddd [kl] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'um %s',
            past: '%s síðani',
            s: 'fá sekund',
            ss: '%d sekundir',
            m: 'ein minuttur',
            mm: '%d minuttir',
            h: 'ein tími',
            hh: '%d tímar',
            d: 'ein dagur',
            dd: '%d dagar',
            M: 'ein mánaður',
            MM: '%d mánaðir',
            y: 'eitt ár',
            yy: '%d ár',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('fr-ca', {
        months: 'janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre'.split(
            '_'
        ),
        monthsShort:
            'janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi'.split('_'),
        weekdaysShort: 'dim._lun._mar._mer._jeu._ven._sam.'.split('_'),
        weekdaysMin: 'di_lu_ma_me_je_ve_sa'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY-MM-DD',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Aujourd’hui à] LT',
            nextDay: '[Demain à] LT',
            nextWeek: 'dddd [à] LT',
            lastDay: '[Hier à] LT',
            lastWeek: 'dddd [dernier à] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'dans %s',
            past: 'il y a %s',
            s: 'quelques secondes',
            ss: '%d secondes',
            m: 'une minute',
            mm: '%d minutes',
            h: 'une heure',
            hh: '%d heures',
            d: 'un jour',
            dd: '%d jours',
            M: 'un mois',
            MM: '%d mois',
            y: 'un an',
            yy: '%d ans',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(er|e)/,
        ordinal: function (number, period) {
            switch (period) {
                // Words with masculine grammatical gender: mois, trimestre, jour
                default:
                case 'M':
                case 'Q':
                case 'D':
                case 'DDD':
                case 'd':
                    return number + (number === 1 ? 'er' : 'e');

                // Words with feminine grammatical gender: semaine
                case 'w':
                case 'W':
                    return number + (number === 1 ? 're' : 'e');
            }
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('fr-ch', {
        months: 'janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre'.split(
            '_'
        ),
        monthsShort:
            'janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi'.split('_'),
        weekdaysShort: 'dim._lun._mar._mer._jeu._ven._sam.'.split('_'),
        weekdaysMin: 'di_lu_ma_me_je_ve_sa'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Aujourd’hui à] LT',
            nextDay: '[Demain à] LT',
            nextWeek: 'dddd [à] LT',
            lastDay: '[Hier à] LT',
            lastWeek: 'dddd [dernier à] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'dans %s',
            past: 'il y a %s',
            s: 'quelques secondes',
            ss: '%d secondes',
            m: 'une minute',
            mm: '%d minutes',
            h: 'une heure',
            hh: '%d heures',
            d: 'un jour',
            dd: '%d jours',
            M: 'un mois',
            MM: '%d mois',
            y: 'un an',
            yy: '%d ans',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(er|e)/,
        ordinal: function (number, period) {
            switch (period) {
                // Words with masculine grammatical gender: mois, trimestre, jour
                default:
                case 'M':
                case 'Q':
                case 'D':
                case 'DDD':
                case 'd':
                    return number + (number === 1 ? 'er' : 'e');

                // Words with feminine grammatical gender: semaine
                case 'w':
                case 'W':
                    return number + (number === 1 ? 're' : 'e');
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsStrictRegex$1 =
            /^(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)/i,
        monthsShortStrictRegex$1 =
            /(janv\.?|févr\.?|mars|avr\.?|mai|juin|juil\.?|août|sept\.?|oct\.?|nov\.?|déc\.?)/i,
        monthsRegex$7 =
            /(janv\.?|févr\.?|mars|avr\.?|mai|juin|juil\.?|août|sept\.?|oct\.?|nov\.?|déc\.?|janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)/i,
        monthsParse$6 = [
            /^janv/i,
            /^févr/i,
            /^mars/i,
            /^avr/i,
            /^mai/i,
            /^juin/i,
            /^juil/i,
            /^août/i,
            /^sept/i,
            /^oct/i,
            /^nov/i,
            /^déc/i,
        ];

    hooks.defineLocale('fr', {
        months: 'janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre'.split(
            '_'
        ),
        monthsShort:
            'janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.'.split(
                '_'
            ),
        monthsRegex: monthsRegex$7,
        monthsShortRegex: monthsRegex$7,
        monthsStrictRegex: monthsStrictRegex$1,
        monthsShortStrictRegex: monthsShortStrictRegex$1,
        monthsParse: monthsParse$6,
        longMonthsParse: monthsParse$6,
        shortMonthsParse: monthsParse$6,
        weekdays: 'dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi'.split('_'),
        weekdaysShort: 'dim._lun._mar._mer._jeu._ven._sam.'.split('_'),
        weekdaysMin: 'di_lu_ma_me_je_ve_sa'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Aujourd’hui à] LT',
            nextDay: '[Demain à] LT',
            nextWeek: 'dddd [à] LT',
            lastDay: '[Hier à] LT',
            lastWeek: 'dddd [dernier à] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'dans %s',
            past: 'il y a %s',
            s: 'quelques secondes',
            ss: '%d secondes',
            m: 'une minute',
            mm: '%d minutes',
            h: 'une heure',
            hh: '%d heures',
            d: 'un jour',
            dd: '%d jours',
            w: 'une semaine',
            ww: '%d semaines',
            M: 'un mois',
            MM: '%d mois',
            y: 'un an',
            yy: '%d ans',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(er|)/,
        ordinal: function (number, period) {
            switch (period) {
                // TODO: Return 'e' when day of month > 1. Move this case inside
                // block for masculine words below.
                // See https://github.com/moment/moment/issues/3375
                case 'D':
                    return number + (number === 1 ? 'er' : '');

                // Words with masculine grammatical gender: mois, trimestre, jour
                default:
                case 'M':
                case 'Q':
                case 'DDD':
                case 'd':
                    return number + (number === 1 ? 'er' : 'e');

                // Words with feminine grammatical gender: semaine
                case 'w':
                case 'W':
                    return number + (number === 1 ? 're' : 'e');
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsShortWithDots =
            'jan._feb._mrt._apr._mai_jun._jul._aug._sep._okt._nov._des.'.split('_'),
        monthsShortWithoutDots =
            'jan_feb_mrt_apr_mai_jun_jul_aug_sep_okt_nov_des'.split('_');

    hooks.defineLocale('fy', {
        months: 'jannewaris_febrewaris_maart_april_maaie_juny_july_augustus_septimber_oktober_novimber_desimber'.split(
            '_'
        ),
        monthsShort: function (m, format) {
            if (!m) {
                return monthsShortWithDots;
            } else if (/-MMM-/.test(format)) {
                return monthsShortWithoutDots[m.month()];
            } else {
                return monthsShortWithDots[m.month()];
            }
        },
        monthsParseExact: true,
        weekdays: 'snein_moandei_tiisdei_woansdei_tongersdei_freed_sneon'.split(
            '_'
        ),
        weekdaysShort: 'si._mo._ti._wo._to._fr._so.'.split('_'),
        weekdaysMin: 'Si_Mo_Ti_Wo_To_Fr_So'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD-MM-YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[hjoed om] LT',
            nextDay: '[moarn om] LT',
            nextWeek: 'dddd [om] LT',
            lastDay: '[juster om] LT',
            lastWeek: '[ôfrûne] dddd [om] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'oer %s',
            past: '%s lyn',
            s: 'in pear sekonden',
            ss: '%d sekonden',
            m: 'ien minút',
            mm: '%d minuten',
            h: 'ien oere',
            hh: '%d oeren',
            d: 'ien dei',
            dd: '%d dagen',
            M: 'ien moanne',
            MM: '%d moannen',
            y: 'ien jier',
            yy: '%d jierren',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(ste|de)/,
        ordinal: function (number) {
            return (
                number +
                (number === 1 || number === 8 || number >= 20 ? 'ste' : 'de')
            );
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var months$6 = [
            'Eanáir',
            'Feabhra',
            'Márta',
            'Aibreán',
            'Bealtaine',
            'Meitheamh',
            'Iúil',
            'Lúnasa',
            'Meán Fómhair',
            'Deireadh Fómhair',
            'Samhain',
            'Nollaig',
        ],
        monthsShort$5 = [
            'Ean',
            'Feabh',
            'Márt',
            'Aib',
            'Beal',
            'Meith',
            'Iúil',
            'Lún',
            'M.F.',
            'D.F.',
            'Samh',
            'Noll',
        ],
        weekdays$1 = [
            'Dé Domhnaigh',
            'Dé Luain',
            'Dé Máirt',
            'Dé Céadaoin',
            'Déardaoin',
            'Dé hAoine',
            'Dé Sathairn',
        ],
        weekdaysShort = ['Domh', 'Luan', 'Máirt', 'Céad', 'Déar', 'Aoine', 'Sath'],
        weekdaysMin = ['Do', 'Lu', 'Má', 'Cé', 'Dé', 'A', 'Sa'];

    hooks.defineLocale('ga', {
        months: months$6,
        monthsShort: monthsShort$5,
        monthsParseExact: true,
        weekdays: weekdays$1,
        weekdaysShort: weekdaysShort,
        weekdaysMin: weekdaysMin,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Inniu ag] LT',
            nextDay: '[Amárach ag] LT',
            nextWeek: 'dddd [ag] LT',
            lastDay: '[Inné ag] LT',
            lastWeek: 'dddd [seo caite] [ag] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'i %s',
            past: '%s ó shin',
            s: 'cúpla soicind',
            ss: '%d soicind',
            m: 'nóiméad',
            mm: '%d nóiméad',
            h: 'uair an chloig',
            hh: '%d uair an chloig',
            d: 'lá',
            dd: '%d lá',
            M: 'mí',
            MM: '%d míonna',
            y: 'bliain',
            yy: '%d bliain',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(d|na|mh)/,
        ordinal: function (number) {
            var output = number === 1 ? 'd' : number % 10 === 2 ? 'na' : 'mh';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var months$7 = [
            'Am Faoilleach',
            'An Gearran',
            'Am Màrt',
            'An Giblean',
            'An Cèitean',
            'An t-Ògmhios',
            'An t-Iuchar',
            'An Lùnastal',
            'An t-Sultain',
            'An Dàmhair',
            'An t-Samhain',
            'An Dùbhlachd',
        ],
        monthsShort$6 = [
            'Faoi',
            'Gear',
            'Màrt',
            'Gibl',
            'Cèit',
            'Ògmh',
            'Iuch',
            'Lùn',
            'Sult',
            'Dàmh',
            'Samh',
            'Dùbh',
        ],
        weekdays$2 = [
            'Didòmhnaich',
            'Diluain',
            'Dimàirt',
            'Diciadain',
            'Diardaoin',
            'Dihaoine',
            'Disathairne',
        ],
        weekdaysShort$1 = ['Did', 'Dil', 'Dim', 'Dic', 'Dia', 'Dih', 'Dis'],
        weekdaysMin$1 = ['Dò', 'Lu', 'Mà', 'Ci', 'Ar', 'Ha', 'Sa'];

    hooks.defineLocale('gd', {
        months: months$7,
        monthsShort: monthsShort$6,
        monthsParseExact: true,
        weekdays: weekdays$2,
        weekdaysShort: weekdaysShort$1,
        weekdaysMin: weekdaysMin$1,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[An-diugh aig] LT',
            nextDay: '[A-màireach aig] LT',
            nextWeek: 'dddd [aig] LT',
            lastDay: '[An-dè aig] LT',
            lastWeek: 'dddd [seo chaidh] [aig] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'ann an %s',
            past: 'bho chionn %s',
            s: 'beagan diogan',
            ss: '%d diogan',
            m: 'mionaid',
            mm: '%d mionaidean',
            h: 'uair',
            hh: '%d uairean',
            d: 'latha',
            dd: '%d latha',
            M: 'mìos',
            MM: '%d mìosan',
            y: 'bliadhna',
            yy: '%d bliadhna',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(d|na|mh)/,
        ordinal: function (number) {
            var output = number === 1 ? 'd' : number % 10 === 2 ? 'na' : 'mh';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('gl', {
        months: 'xaneiro_febreiro_marzo_abril_maio_xuño_xullo_agosto_setembro_outubro_novembro_decembro'.split(
            '_'
        ),
        monthsShort:
            'xan._feb._mar._abr._mai._xuñ._xul._ago._set._out._nov._dec.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'domingo_luns_martes_mércores_xoves_venres_sábado'.split('_'),
        weekdaysShort: 'dom._lun._mar._mér._xov._ven._sáb.'.split('_'),
        weekdaysMin: 'do_lu_ma_mé_xo_ve_sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D [de] MMMM [de] YYYY',
            LLL: 'D [de] MMMM [de] YYYY H:mm',
            LLLL: 'dddd, D [de] MMMM [de] YYYY H:mm',
        },
        calendar: {
            sameDay: function () {
                return '[hoxe ' + (this.hours() !== 1 ? 'ás' : 'á') + '] LT';
            },
            nextDay: function () {
                return '[mañá ' + (this.hours() !== 1 ? 'ás' : 'á') + '] LT';
            },
            nextWeek: function () {
                return 'dddd [' + (this.hours() !== 1 ? 'ás' : 'a') + '] LT';
            },
            lastDay: function () {
                return '[onte ' + (this.hours() !== 1 ? 'á' : 'a') + '] LT';
            },
            lastWeek: function () {
                return (
                    '[o] dddd [pasado ' + (this.hours() !== 1 ? 'ás' : 'a') + '] LT'
                );
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: function (str) {
                if (str.indexOf('un') === 0) {
                    return 'n' + str;
                }
                return 'en ' + str;
            },
            past: 'hai %s',
            s: 'uns segundos',
            ss: '%d segundos',
            m: 'un minuto',
            mm: '%d minutos',
            h: 'unha hora',
            hh: '%d horas',
            d: 'un día',
            dd: '%d días',
            M: 'un mes',
            MM: '%d meses',
            y: 'un ano',
            yy: '%d anos',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function processRelativeTime$4(number, withoutSuffix, key, isFuture) {
        var format = {
            s: ['थोडया सॅकंडांनी', 'थोडे सॅकंड'],
            ss: [number + ' सॅकंडांनी', number + ' सॅकंड'],
            m: ['एका मिणटान', 'एक मिनूट'],
            mm: [number + ' मिणटांनी', number + ' मिणटां'],
            h: ['एका वरान', 'एक वर'],
            hh: [number + ' वरांनी', number + ' वरां'],
            d: ['एका दिसान', 'एक दीस'],
            dd: [number + ' दिसांनी', number + ' दीस'],
            M: ['एका म्हयन्यान', 'एक म्हयनो'],
            MM: [number + ' म्हयन्यानी', number + ' म्हयने'],
            y: ['एका वर्सान', 'एक वर्स'],
            yy: [number + ' वर्सांनी', number + ' वर्सां'],
        };
        return isFuture ? format[key][0] : format[key][1];
    }

    hooks.defineLocale('gom-deva', {
        months: {
            standalone:
                'जानेवारी_फेब्रुवारी_मार्च_एप्रील_मे_जून_जुलय_ऑगस्ट_सप्टेंबर_ऑक्टोबर_नोव्हेंबर_डिसेंबर'.split(
                    '_'
                ),
            format: 'जानेवारीच्या_फेब्रुवारीच्या_मार्चाच्या_एप्रीलाच्या_मेयाच्या_जूनाच्या_जुलयाच्या_ऑगस्टाच्या_सप्टेंबराच्या_ऑक्टोबराच्या_नोव्हेंबराच्या_डिसेंबराच्या'.split(
                '_'
            ),
            isFormat: /MMMM(\s)+D[oD]?/,
        },
        monthsShort:
            'जाने._फेब्रु._मार्च_एप्री._मे_जून_जुल._ऑग._सप्टें._ऑक्टो._नोव्हें._डिसें.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'आयतार_सोमार_मंगळार_बुधवार_बिरेस्तार_सुक्रार_शेनवार'.split('_'),
        weekdaysShort: 'आयत._सोम._मंगळ._बुध._ब्रेस्त._सुक्र._शेन.'.split('_'),
        weekdaysMin: 'आ_सो_मं_बु_ब्रे_सु_शे'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'A h:mm [वाजतां]',
            LTS: 'A h:mm:ss [वाजतां]',
            L: 'DD-MM-YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY A h:mm [वाजतां]',
            LLLL: 'dddd, MMMM Do, YYYY, A h:mm [वाजतां]',
            llll: 'ddd, D MMM YYYY, A h:mm [वाजतां]',
        },
        calendar: {
            sameDay: '[आयज] LT',
            nextDay: '[फाल्यां] LT',
            nextWeek: '[फुडलो] dddd[,] LT',
            lastDay: '[काल] LT',
            lastWeek: '[फाटलो] dddd[,] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s',
            past: '%s आदीं',
            s: processRelativeTime$4,
            ss: processRelativeTime$4,
            m: processRelativeTime$4,
            mm: processRelativeTime$4,
            h: processRelativeTime$4,
            hh: processRelativeTime$4,
            d: processRelativeTime$4,
            dd: processRelativeTime$4,
            M: processRelativeTime$4,
            MM: processRelativeTime$4,
            y: processRelativeTime$4,
            yy: processRelativeTime$4,
        },
        dayOfMonthOrdinalParse: /\d{1,2}(वेर)/,
        ordinal: function (number, period) {
            switch (period) {
                // the ordinal 'वेर' only applies to day of the month
                case 'D':
                    return number + 'वेर';
                default:
                case 'M':
                case 'Q':
                case 'DDD':
                case 'd':
                case 'w':
                case 'W':
                    return number;
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week
            doy: 3, // The week that contains Jan 4th is the first week of the year (7 + 0 - 4)
        },
        meridiemParse: /राती|सकाळीं|दनपारां|सांजे/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'राती') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'सकाळीं') {
                return hour;
            } else if (meridiem === 'दनपारां') {
                return hour > 12 ? hour : hour + 12;
            } else if (meridiem === 'सांजे') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'राती';
            } else if (hour < 12) {
                return 'सकाळीं';
            } else if (hour < 16) {
                return 'दनपारां';
            } else if (hour < 20) {
                return 'सांजे';
            } else {
                return 'राती';
            }
        },
    });

    //! moment.js locale configuration

    function processRelativeTime$5(number, withoutSuffix, key, isFuture) {
        var format = {
            s: ['thoddea sekondamni', 'thodde sekond'],
            ss: [number + ' sekondamni', number + ' sekond'],
            m: ['eka mintan', 'ek minut'],
            mm: [number + ' mintamni', number + ' mintam'],
            h: ['eka voran', 'ek vor'],
            hh: [number + ' voramni', number + ' voram'],
            d: ['eka disan', 'ek dis'],
            dd: [number + ' disamni', number + ' dis'],
            M: ['eka mhoinean', 'ek mhoino'],
            MM: [number + ' mhoineamni', number + ' mhoine'],
            y: ['eka vorsan', 'ek voros'],
            yy: [number + ' vorsamni', number + ' vorsam'],
        };
        return isFuture ? format[key][0] : format[key][1];
    }

    hooks.defineLocale('gom-latn', {
        months: {
            standalone:
                'Janer_Febrer_Mars_Abril_Mai_Jun_Julai_Agost_Setembr_Otubr_Novembr_Dezembr'.split(
                    '_'
                ),
            format: 'Janerachea_Febrerachea_Marsachea_Abrilachea_Maiachea_Junachea_Julaiachea_Agostachea_Setembrachea_Otubrachea_Novembrachea_Dezembrachea'.split(
                '_'
            ),
            isFormat: /MMMM(\s)+D[oD]?/,
        },
        monthsShort:
            'Jan._Feb._Mars_Abr._Mai_Jun_Jul._Ago._Set._Otu._Nov._Dez.'.split('_'),
        monthsParseExact: true,
        weekdays: "Aitar_Somar_Mongllar_Budhvar_Birestar_Sukrar_Son'var".split('_'),
        weekdaysShort: 'Ait._Som._Mon._Bud._Bre._Suk._Son.'.split('_'),
        weekdaysMin: 'Ai_Sm_Mo_Bu_Br_Su_Sn'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'A h:mm [vazta]',
            LTS: 'A h:mm:ss [vazta]',
            L: 'DD-MM-YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY A h:mm [vazta]',
            LLLL: 'dddd, MMMM Do, YYYY, A h:mm [vazta]',
            llll: 'ddd, D MMM YYYY, A h:mm [vazta]',
        },
        calendar: {
            sameDay: '[Aiz] LT',
            nextDay: '[Faleam] LT',
            nextWeek: '[Fuddlo] dddd[,] LT',
            lastDay: '[Kal] LT',
            lastWeek: '[Fattlo] dddd[,] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s',
            past: '%s adim',
            s: processRelativeTime$5,
            ss: processRelativeTime$5,
            m: processRelativeTime$5,
            mm: processRelativeTime$5,
            h: processRelativeTime$5,
            hh: processRelativeTime$5,
            d: processRelativeTime$5,
            dd: processRelativeTime$5,
            M: processRelativeTime$5,
            MM: processRelativeTime$5,
            y: processRelativeTime$5,
            yy: processRelativeTime$5,
        },
        dayOfMonthOrdinalParse: /\d{1,2}(er)/,
        ordinal: function (number, period) {
            switch (period) {
                // the ordinal 'er' only applies to day of the month
                case 'D':
                    return number + 'er';
                default:
                case 'M':
                case 'Q':
                case 'DDD':
                case 'd':
                case 'w':
                case 'W':
                    return number;
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week
            doy: 3, // The week that contains Jan 4th is the first week of the year (7 + 0 - 4)
        },
        meridiemParse: /rati|sokallim|donparam|sanje/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'rati') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'sokallim') {
                return hour;
            } else if (meridiem === 'donparam') {
                return hour > 12 ? hour : hour + 12;
            } else if (meridiem === 'sanje') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'rati';
            } else if (hour < 12) {
                return 'sokallim';
            } else if (hour < 16) {
                return 'donparam';
            } else if (hour < 20) {
                return 'sanje';
            } else {
                return 'rati';
            }
        },
    });

    //! moment.js locale configuration

    var symbolMap$7 = {
            1: '૧',
            2: '૨',
            3: '૩',
            4: '૪',
            5: '૫',
            6: '૬',
            7: '૭',
            8: '૮',
            9: '૯',
            0: '૦',
        },
        numberMap$6 = {
            '૧': '1',
            '૨': '2',
            '૩': '3',
            '૪': '4',
            '૫': '5',
            '૬': '6',
            '૭': '7',
            '૮': '8',
            '૯': '9',
            '૦': '0',
        };

    hooks.defineLocale('gu', {
        months: 'જાન્યુઆરી_ફેબ્રુઆરી_માર્ચ_એપ્રિલ_મે_જૂન_જુલાઈ_ઑગસ્ટ_સપ્ટેમ્બર_ઑક્ટ્બર_નવેમ્બર_ડિસેમ્બર'.split(
            '_'
        ),
        monthsShort:
            'જાન્યુ._ફેબ્રુ._માર્ચ_એપ્રિ._મે_જૂન_જુલા._ઑગ._સપ્ટે._ઑક્ટ્._નવે._ડિસે.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'રવિવાર_સોમવાર_મંગળવાર_બુધ્વાર_ગુરુવાર_શુક્રવાર_શનિવાર'.split(
            '_'
        ),
        weekdaysShort: 'રવિ_સોમ_મંગળ_બુધ્_ગુરુ_શુક્ર_શનિ'.split('_'),
        weekdaysMin: 'ર_સો_મં_બુ_ગુ_શુ_શ'.split('_'),
        longDateFormat: {
            LT: 'A h:mm વાગ્યે',
            LTS: 'A h:mm:ss વાગ્યે',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm વાગ્યે',
            LLLL: 'dddd, D MMMM YYYY, A h:mm વાગ્યે',
        },
        calendar: {
            sameDay: '[આજ] LT',
            nextDay: '[કાલે] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[ગઇકાલે] LT',
            lastWeek: '[પાછલા] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s મા',
            past: '%s પહેલા',
            s: 'અમુક પળો',
            ss: '%d સેકંડ',
            m: 'એક મિનિટ',
            mm: '%d મિનિટ',
            h: 'એક કલાક',
            hh: '%d કલાક',
            d: 'એક દિવસ',
            dd: '%d દિવસ',
            M: 'એક મહિનો',
            MM: '%d મહિનો',
            y: 'એક વર્ષ',
            yy: '%d વર્ષ',
        },
        preparse: function (string) {
            return string.replace(/[૧૨૩૪૫૬૭૮૯૦]/g, function (match) {
                return numberMap$6[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$7[match];
            });
        },
        // Gujarati notation for meridiems are quite fuzzy in practice. While there exists
        // a rigid notion of a 'Pahar' it is not used as rigidly in modern Gujarati.
        meridiemParse: /રાત|બપોર|સવાર|સાંજ/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'રાત') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'સવાર') {
                return hour;
            } else if (meridiem === 'બપોર') {
                return hour >= 10 ? hour : hour + 12;
            } else if (meridiem === 'સાંજ') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'રાત';
            } else if (hour < 10) {
                return 'સવાર';
            } else if (hour < 17) {
                return 'બપોર';
            } else if (hour < 20) {
                return 'સાંજ';
            } else {
                return 'રાત';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('he', {
        months: 'ינואר_פברואר_מרץ_אפריל_מאי_יוני_יולי_אוגוסט_ספטמבר_אוקטובר_נובמבר_דצמבר'.split(
            '_'
        ),
        monthsShort:
            'ינו׳_פבר׳_מרץ_אפר׳_מאי_יוני_יולי_אוג׳_ספט׳_אוק׳_נוב׳_דצמ׳'.split('_'),
        weekdays: 'ראשון_שני_שלישי_רביעי_חמישי_שישי_שבת'.split('_'),
        weekdaysShort: 'א׳_ב׳_ג׳_ד׳_ה׳_ו׳_ש׳'.split('_'),
        weekdaysMin: 'א_ב_ג_ד_ה_ו_ש'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D [ב]MMMM YYYY',
            LLL: 'D [ב]MMMM YYYY HH:mm',
            LLLL: 'dddd, D [ב]MMMM YYYY HH:mm',
            l: 'D/M/YYYY',
            ll: 'D MMM YYYY',
            lll: 'D MMM YYYY HH:mm',
            llll: 'ddd, D MMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[היום ב־]LT',
            nextDay: '[מחר ב־]LT',
            nextWeek: 'dddd [בשעה] LT',
            lastDay: '[אתמול ב־]LT',
            lastWeek: '[ביום] dddd [האחרון בשעה] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'בעוד %s',
            past: 'לפני %s',
            s: 'מספר שניות',
            ss: '%d שניות',
            m: 'דקה',
            mm: '%d דקות',
            h: 'שעה',
            hh: function (number) {
                if (number === 2) {
                    return 'שעתיים';
                }
                return number + ' שעות';
            },
            d: 'יום',
            dd: function (number) {
                if (number === 2) {
                    return 'יומיים';
                }
                return number + ' ימים';
            },
            M: 'חודש',
            MM: function (number) {
                if (number === 2) {
                    return 'חודשיים';
                }
                return number + ' חודשים';
            },
            y: 'שנה',
            yy: function (number) {
                if (number === 2) {
                    return 'שנתיים';
                } else if (number % 10 === 0 && number !== 10) {
                    return number + ' שנה';
                }
                return number + ' שנים';
            },
        },
        meridiemParse:
            /אחה"צ|לפנה"צ|אחרי הצהריים|לפני הצהריים|לפנות בוקר|בבוקר|בערב/i,
        isPM: function (input) {
            return /^(אחה"צ|אחרי הצהריים|בערב)$/.test(input);
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 5) {
                return 'לפנות בוקר';
            } else if (hour < 10) {
                return 'בבוקר';
            } else if (hour < 12) {
                return isLower ? 'לפנה"צ' : 'לפני הצהריים';
            } else if (hour < 18) {
                return isLower ? 'אחה"צ' : 'אחרי הצהריים';
            } else {
                return 'בערב';
            }
        },
    });

    //! moment.js locale configuration

    var symbolMap$8 = {
            1: '१',
            2: '२',
            3: '३',
            4: '४',
            5: '५',
            6: '६',
            7: '७',
            8: '८',
            9: '९',
            0: '०',
        },
        numberMap$7 = {
            '१': '1',
            '२': '2',
            '३': '3',
            '४': '4',
            '५': '5',
            '६': '6',
            '७': '7',
            '८': '8',
            '९': '9',
            '०': '0',
        },
        monthsParse$7 = [
            /^जन/i,
            /^फ़र|फर/i,
            /^मार्च/i,
            /^अप्रै/i,
            /^मई/i,
            /^जून/i,
            /^जुल/i,
            /^अग/i,
            /^सितं|सित/i,
            /^अक्टू/i,
            /^नव|नवं/i,
            /^दिसं|दिस/i,
        ],
        shortMonthsParse = [
            /^जन/i,
            /^फ़र/i,
            /^मार्च/i,
            /^अप्रै/i,
            /^मई/i,
            /^जून/i,
            /^जुल/i,
            /^अग/i,
            /^सित/i,
            /^अक्टू/i,
            /^नव/i,
            /^दिस/i,
        ];

    hooks.defineLocale('hi', {
        months: {
            format: 'जनवरी_फ़रवरी_मार्च_अप्रैल_मई_जून_जुलाई_अगस्त_सितम्बर_अक्टूबर_नवम्बर_दिसम्बर'.split(
                '_'
            ),
            standalone:
                'जनवरी_फरवरी_मार्च_अप्रैल_मई_जून_जुलाई_अगस्त_सितंबर_अक्टूबर_नवंबर_दिसंबर'.split(
                    '_'
                ),
        },
        monthsShort:
            'जन._फ़र._मार्च_अप्रै._मई_जून_जुल._अग._सित._अक्टू._नव._दिस.'.split('_'),
        weekdays: 'रविवार_सोमवार_मंगलवार_बुधवार_गुरूवार_शुक्रवार_शनिवार'.split('_'),
        weekdaysShort: 'रवि_सोम_मंगल_बुध_गुरू_शुक्र_शनि'.split('_'),
        weekdaysMin: 'र_सो_मं_बु_गु_शु_श'.split('_'),
        longDateFormat: {
            LT: 'A h:mm बजे',
            LTS: 'A h:mm:ss बजे',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm बजे',
            LLLL: 'dddd, D MMMM YYYY, A h:mm बजे',
        },

        monthsParse: monthsParse$7,
        longMonthsParse: monthsParse$7,
        shortMonthsParse: shortMonthsParse,

        monthsRegex:
            /^(जनवरी|जन\.?|फ़रवरी|फरवरी|फ़र\.?|मार्च?|अप्रैल|अप्रै\.?|मई?|जून?|जुलाई|जुल\.?|अगस्त|अग\.?|सितम्बर|सितंबर|सित\.?|अक्टूबर|अक्टू\.?|नवम्बर|नवंबर|नव\.?|दिसम्बर|दिसंबर|दिस\.?)/i,

        monthsShortRegex:
            /^(जनवरी|जन\.?|फ़रवरी|फरवरी|फ़र\.?|मार्च?|अप्रैल|अप्रै\.?|मई?|जून?|जुलाई|जुल\.?|अगस्त|अग\.?|सितम्बर|सितंबर|सित\.?|अक्टूबर|अक्टू\.?|नवम्बर|नवंबर|नव\.?|दिसम्बर|दिसंबर|दिस\.?)/i,

        monthsStrictRegex:
            /^(जनवरी?|फ़रवरी|फरवरी?|मार्च?|अप्रैल?|मई?|जून?|जुलाई?|अगस्त?|सितम्बर|सितंबर|सित?\.?|अक्टूबर|अक्टू\.?|नवम्बर|नवंबर?|दिसम्बर|दिसंबर?)/i,

        monthsShortStrictRegex:
            /^(जन\.?|फ़र\.?|मार्च?|अप्रै\.?|मई?|जून?|जुल\.?|अग\.?|सित\.?|अक्टू\.?|नव\.?|दिस\.?)/i,

        calendar: {
            sameDay: '[आज] LT',
            nextDay: '[कल] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[कल] LT',
            lastWeek: '[पिछले] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s में',
            past: '%s पहले',
            s: 'कुछ ही क्षण',
            ss: '%d सेकंड',
            m: 'एक मिनट',
            mm: '%d मिनट',
            h: 'एक घंटा',
            hh: '%d घंटे',
            d: 'एक दिन',
            dd: '%d दिन',
            M: 'एक महीने',
            MM: '%d महीने',
            y: 'एक वर्ष',
            yy: '%d वर्ष',
        },
        preparse: function (string) {
            return string.replace(/[१२३४५६७८९०]/g, function (match) {
                return numberMap$7[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$8[match];
            });
        },
        // Hindi notation for meridiems are quite fuzzy in practice. While there exists
        // a rigid notion of a 'Pahar' it is not used as rigidly in modern Hindi.
        meridiemParse: /रात|सुबह|दोपहर|शाम/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'रात') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'सुबह') {
                return hour;
            } else if (meridiem === 'दोपहर') {
                return hour >= 10 ? hour : hour + 12;
            } else if (meridiem === 'शाम') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'रात';
            } else if (hour < 10) {
                return 'सुबह';
            } else if (hour < 17) {
                return 'दोपहर';
            } else if (hour < 20) {
                return 'शाम';
            } else {
                return 'रात';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function translate$3(number, withoutSuffix, key) {
        var result = number + ' ';
        switch (key) {
            case 'ss':
                if (number === 1) {
                    result += 'sekunda';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'sekunde';
                } else {
                    result += 'sekundi';
                }
                return result;
            case 'm':
                return withoutSuffix ? 'jedna minuta' : 'jedne minute';
            case 'mm':
                if (number === 1) {
                    result += 'minuta';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'minute';
                } else {
                    result += 'minuta';
                }
                return result;
            case 'h':
                return withoutSuffix ? 'jedan sat' : 'jednog sata';
            case 'hh':
                if (number === 1) {
                    result += 'sat';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'sata';
                } else {
                    result += 'sati';
                }
                return result;
            case 'dd':
                if (number === 1) {
                    result += 'dan';
                } else {
                    result += 'dana';
                }
                return result;
            case 'MM':
                if (number === 1) {
                    result += 'mjesec';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'mjeseca';
                } else {
                    result += 'mjeseci';
                }
                return result;
            case 'yy':
                if (number === 1) {
                    result += 'godina';
                } else if (number === 2 || number === 3 || number === 4) {
                    result += 'godine';
                } else {
                    result += 'godina';
                }
                return result;
        }
    }

    hooks.defineLocale('hr', {
        months: {
            format: 'siječnja_veljače_ožujka_travnja_svibnja_lipnja_srpnja_kolovoza_rujna_listopada_studenoga_prosinca'.split(
                '_'
            ),
            standalone:
                'siječanj_veljača_ožujak_travanj_svibanj_lipanj_srpanj_kolovoz_rujan_listopad_studeni_prosinac'.split(
                    '_'
                ),
        },
        monthsShort:
            'sij._velj._ožu._tra._svi._lip._srp._kol._ruj._lis._stu._pro.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'nedjelja_ponedjeljak_utorak_srijeda_četvrtak_petak_subota'.split(
            '_'
        ),
        weekdaysShort: 'ned._pon._uto._sri._čet._pet._sub.'.split('_'),
        weekdaysMin: 'ne_po_ut_sr_če_pe_su'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'Do MMMM YYYY',
            LLL: 'Do MMMM YYYY H:mm',
            LLLL: 'dddd, Do MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[danas u] LT',
            nextDay: '[sutra u] LT',
            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[u] [nedjelju] [u] LT';
                    case 3:
                        return '[u] [srijedu] [u] LT';
                    case 6:
                        return '[u] [subotu] [u] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[u] dddd [u] LT';
                }
            },
            lastDay: '[jučer u] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[prošlu] [nedjelju] [u] LT';
                    case 3:
                        return '[prošlu] [srijedu] [u] LT';
                    case 6:
                        return '[prošle] [subote] [u] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[prošli] dddd [u] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'za %s',
            past: 'prije %s',
            s: 'par sekundi',
            ss: translate$3,
            m: translate$3,
            mm: translate$3,
            h: translate$3,
            hh: translate$3,
            d: 'dan',
            dd: translate$3,
            M: 'mjesec',
            MM: translate$3,
            y: 'godinu',
            yy: translate$3,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var weekEndings =
        'vasárnap hétfőn kedden szerdán csütörtökön pénteken szombaton'.split(' ');
    function translate$4(number, withoutSuffix, key, isFuture) {
        var num = number;
        switch (key) {
            case 's':
                return isFuture || withoutSuffix
                    ? 'néhány másodperc'
                    : 'néhány másodperce';
            case 'ss':
                return num + (isFuture || withoutSuffix)
                    ? ' másodperc'
                    : ' másodperce';
            case 'm':
                return 'egy' + (isFuture || withoutSuffix ? ' perc' : ' perce');
            case 'mm':
                return num + (isFuture || withoutSuffix ? ' perc' : ' perce');
            case 'h':
                return 'egy' + (isFuture || withoutSuffix ? ' óra' : ' órája');
            case 'hh':
                return num + (isFuture || withoutSuffix ? ' óra' : ' órája');
            case 'd':
                return 'egy' + (isFuture || withoutSuffix ? ' nap' : ' napja');
            case 'dd':
                return num + (isFuture || withoutSuffix ? ' nap' : ' napja');
            case 'M':
                return 'egy' + (isFuture || withoutSuffix ? ' hónap' : ' hónapja');
            case 'MM':
                return num + (isFuture || withoutSuffix ? ' hónap' : ' hónapja');
            case 'y':
                return 'egy' + (isFuture || withoutSuffix ? ' év' : ' éve');
            case 'yy':
                return num + (isFuture || withoutSuffix ? ' év' : ' éve');
        }
        return '';
    }
    function week(isFuture) {
        return (
            (isFuture ? '' : '[múlt] ') +
            '[' +
            weekEndings[this.day()] +
            '] LT[-kor]'
        );
    }

    hooks.defineLocale('hu', {
        months: 'január_február_március_április_május_június_július_augusztus_szeptember_október_november_december'.split(
            '_'
        ),
        monthsShort:
            'jan._feb._márc._ápr._máj._jún._júl._aug._szept._okt._nov._dec.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'vasárnap_hétfő_kedd_szerda_csütörtök_péntek_szombat'.split('_'),
        weekdaysShort: 'vas_hét_kedd_sze_csüt_pén_szo'.split('_'),
        weekdaysMin: 'v_h_k_sze_cs_p_szo'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'YYYY.MM.DD.',
            LL: 'YYYY. MMMM D.',
            LLL: 'YYYY. MMMM D. H:mm',
            LLLL: 'YYYY. MMMM D., dddd H:mm',
        },
        meridiemParse: /de|du/i,
        isPM: function (input) {
            return input.charAt(1).toLowerCase() === 'u';
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 12) {
                return isLower === true ? 'de' : 'DE';
            } else {
                return isLower === true ? 'du' : 'DU';
            }
        },
        calendar: {
            sameDay: '[ma] LT[-kor]',
            nextDay: '[holnap] LT[-kor]',
            nextWeek: function () {
                return week.call(this, true);
            },
            lastDay: '[tegnap] LT[-kor]',
            lastWeek: function () {
                return week.call(this, false);
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s múlva',
            past: '%s',
            s: translate$4,
            ss: translate$4,
            m: translate$4,
            mm: translate$4,
            h: translate$4,
            hh: translate$4,
            d: translate$4,
            dd: translate$4,
            M: translate$4,
            MM: translate$4,
            y: translate$4,
            yy: translate$4,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('hy-am', {
        months: {
            format: 'հունվարի_փետրվարի_մարտի_ապրիլի_մայիսի_հունիսի_հուլիսի_օգոստոսի_սեպտեմբերի_հոկտեմբերի_նոյեմբերի_դեկտեմբերի'.split(
                '_'
            ),
            standalone:
                'հունվար_փետրվար_մարտ_ապրիլ_մայիս_հունիս_հուլիս_օգոստոս_սեպտեմբեր_հոկտեմբեր_նոյեմբեր_դեկտեմբեր'.split(
                    '_'
                ),
        },
        monthsShort: 'հնվ_փտր_մրտ_ապր_մյս_հնս_հլս_օգս_սպտ_հկտ_նմբ_դկտ'.split('_'),
        weekdays:
            'կիրակի_երկուշաբթի_երեքշաբթի_չորեքշաբթի_հինգշաբթի_ուրբաթ_շաբաթ'.split(
                '_'
            ),
        weekdaysShort: 'կրկ_երկ_երք_չրք_հնգ_ուրբ_շբթ'.split('_'),
        weekdaysMin: 'կրկ_երկ_երք_չրք_հնգ_ուրբ_շբթ'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY թ.',
            LLL: 'D MMMM YYYY թ., HH:mm',
            LLLL: 'dddd, D MMMM YYYY թ., HH:mm',
        },
        calendar: {
            sameDay: '[այսօր] LT',
            nextDay: '[վաղը] LT',
            lastDay: '[երեկ] LT',
            nextWeek: function () {
                return 'dddd [օրը ժամը] LT';
            },
            lastWeek: function () {
                return '[անցած] dddd [օրը ժամը] LT';
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s հետո',
            past: '%s առաջ',
            s: 'մի քանի վայրկյան',
            ss: '%d վայրկյան',
            m: 'րոպե',
            mm: '%d րոպե',
            h: 'ժամ',
            hh: '%d ժամ',
            d: 'օր',
            dd: '%d օր',
            M: 'ամիս',
            MM: '%d ամիս',
            y: 'տարի',
            yy: '%d տարի',
        },
        meridiemParse: /գիշերվա|առավոտվա|ցերեկվա|երեկոյան/,
        isPM: function (input) {
            return /^(ցերեկվա|երեկոյան)$/.test(input);
        },
        meridiem: function (hour) {
            if (hour < 4) {
                return 'գիշերվա';
            } else if (hour < 12) {
                return 'առավոտվա';
            } else if (hour < 17) {
                return 'ցերեկվա';
            } else {
                return 'երեկոյան';
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}|\d{1,2}-(ին|րդ)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'DDD':
                case 'w':
                case 'W':
                case 'DDDo':
                    if (number === 1) {
                        return number + '-ին';
                    }
                    return number + '-րդ';
                default:
                    return number;
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('id', {
        months: 'Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_November_Desember'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_Mei_Jun_Jul_Agt_Sep_Okt_Nov_Des'.split('_'),
        weekdays: 'Minggu_Senin_Selasa_Rabu_Kamis_Jumat_Sabtu'.split('_'),
        weekdaysShort: 'Min_Sen_Sel_Rab_Kam_Jum_Sab'.split('_'),
        weekdaysMin: 'Mg_Sn_Sl_Rb_Km_Jm_Sb'.split('_'),
        longDateFormat: {
            LT: 'HH.mm',
            LTS: 'HH.mm.ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY [pukul] HH.mm',
            LLLL: 'dddd, D MMMM YYYY [pukul] HH.mm',
        },
        meridiemParse: /pagi|siang|sore|malam/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'pagi') {
                return hour;
            } else if (meridiem === 'siang') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'sore' || meridiem === 'malam') {
                return hour + 12;
            }
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 11) {
                return 'pagi';
            } else if (hours < 15) {
                return 'siang';
            } else if (hours < 19) {
                return 'sore';
            } else {
                return 'malam';
            }
        },
        calendar: {
            sameDay: '[Hari ini pukul] LT',
            nextDay: '[Besok pukul] LT',
            nextWeek: 'dddd [pukul] LT',
            lastDay: '[Kemarin pukul] LT',
            lastWeek: 'dddd [lalu pukul] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'dalam %s',
            past: '%s yang lalu',
            s: 'beberapa detik',
            ss: '%d detik',
            m: 'semenit',
            mm: '%d menit',
            h: 'sejam',
            hh: '%d jam',
            d: 'sehari',
            dd: '%d hari',
            M: 'sebulan',
            MM: '%d bulan',
            y: 'setahun',
            yy: '%d tahun',
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function plural$2(n) {
        if (n % 100 === 11) {
            return true;
        } else if (n % 10 === 1) {
            return false;
        }
        return true;
    }
    function translate$5(number, withoutSuffix, key, isFuture) {
        var result = number + ' ';
        switch (key) {
            case 's':
                return withoutSuffix || isFuture
                    ? 'nokkrar sekúndur'
                    : 'nokkrum sekúndum';
            case 'ss':
                if (plural$2(number)) {
                    return (
                        result +
                        (withoutSuffix || isFuture ? 'sekúndur' : 'sekúndum')
                    );
                }
                return result + 'sekúnda';
            case 'm':
                return withoutSuffix ? 'mínúta' : 'mínútu';
            case 'mm':
                if (plural$2(number)) {
                    return (
                        result + (withoutSuffix || isFuture ? 'mínútur' : 'mínútum')
                    );
                } else if (withoutSuffix) {
                    return result + 'mínúta';
                }
                return result + 'mínútu';
            case 'hh':
                if (plural$2(number)) {
                    return (
                        result +
                        (withoutSuffix || isFuture
                            ? 'klukkustundir'
                            : 'klukkustundum')
                    );
                }
                return result + 'klukkustund';
            case 'd':
                if (withoutSuffix) {
                    return 'dagur';
                }
                return isFuture ? 'dag' : 'degi';
            case 'dd':
                if (plural$2(number)) {
                    if (withoutSuffix) {
                        return result + 'dagar';
                    }
                    return result + (isFuture ? 'daga' : 'dögum');
                } else if (withoutSuffix) {
                    return result + 'dagur';
                }
                return result + (isFuture ? 'dag' : 'degi');
            case 'M':
                if (withoutSuffix) {
                    return 'mánuður';
                }
                return isFuture ? 'mánuð' : 'mánuði';
            case 'MM':
                if (plural$2(number)) {
                    if (withoutSuffix) {
                        return result + 'mánuðir';
                    }
                    return result + (isFuture ? 'mánuði' : 'mánuðum');
                } else if (withoutSuffix) {
                    return result + 'mánuður';
                }
                return result + (isFuture ? 'mánuð' : 'mánuði');
            case 'y':
                return withoutSuffix || isFuture ? 'ár' : 'ári';
            case 'yy':
                if (plural$2(number)) {
                    return result + (withoutSuffix || isFuture ? 'ár' : 'árum');
                }
                return result + (withoutSuffix || isFuture ? 'ár' : 'ári');
        }
    }

    hooks.defineLocale('is', {
        months: 'janúar_febrúar_mars_apríl_maí_júní_júlí_ágúst_september_október_nóvember_desember'.split(
            '_'
        ),
        monthsShort: 'jan_feb_mar_apr_maí_jún_júl_ágú_sep_okt_nóv_des'.split('_'),
        weekdays:
            'sunnudagur_mánudagur_þriðjudagur_miðvikudagur_fimmtudagur_föstudagur_laugardagur'.split(
                '_'
            ),
        weekdaysShort: 'sun_mán_þri_mið_fim_fös_lau'.split('_'),
        weekdaysMin: 'Su_Má_Þr_Mi_Fi_Fö_La'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY [kl.] H:mm',
            LLLL: 'dddd, D. MMMM YYYY [kl.] H:mm',
        },
        calendar: {
            sameDay: '[í dag kl.] LT',
            nextDay: '[á morgun kl.] LT',
            nextWeek: 'dddd [kl.] LT',
            lastDay: '[í gær kl.] LT',
            lastWeek: '[síðasta] dddd [kl.] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'eftir %s',
            past: 'fyrir %s síðan',
            s: translate$5,
            ss: translate$5,
            m: translate$5,
            mm: translate$5,
            h: 'klukkustund',
            hh: translate$5,
            d: translate$5,
            dd: translate$5,
            M: translate$5,
            MM: translate$5,
            y: translate$5,
            yy: translate$5,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('it-ch', {
        months: 'gennaio_febbraio_marzo_aprile_maggio_giugno_luglio_agosto_settembre_ottobre_novembre_dicembre'.split(
            '_'
        ),
        monthsShort: 'gen_feb_mar_apr_mag_giu_lug_ago_set_ott_nov_dic'.split('_'),
        weekdays: 'domenica_lunedì_martedì_mercoledì_giovedì_venerdì_sabato'.split(
            '_'
        ),
        weekdaysShort: 'dom_lun_mar_mer_gio_ven_sab'.split('_'),
        weekdaysMin: 'do_lu_ma_me_gi_ve_sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Oggi alle] LT',
            nextDay: '[Domani alle] LT',
            nextWeek: 'dddd [alle] LT',
            lastDay: '[Ieri alle] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[la scorsa] dddd [alle] LT';
                    default:
                        return '[lo scorso] dddd [alle] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: function (s) {
                return (/^[0-9].+$/.test(s) ? 'tra' : 'in') + ' ' + s;
            },
            past: '%s fa',
            s: 'alcuni secondi',
            ss: '%d secondi',
            m: 'un minuto',
            mm: '%d minuti',
            h: "un'ora",
            hh: '%d ore',
            d: 'un giorno',
            dd: '%d giorni',
            M: 'un mese',
            MM: '%d mesi',
            y: 'un anno',
            yy: '%d anni',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('it', {
        months: 'gennaio_febbraio_marzo_aprile_maggio_giugno_luglio_agosto_settembre_ottobre_novembre_dicembre'.split(
            '_'
        ),
        monthsShort: 'gen_feb_mar_apr_mag_giu_lug_ago_set_ott_nov_dic'.split('_'),
        weekdays: 'domenica_lunedì_martedì_mercoledì_giovedì_venerdì_sabato'.split(
            '_'
        ),
        weekdaysShort: 'dom_lun_mar_mer_gio_ven_sab'.split('_'),
        weekdaysMin: 'do_lu_ma_me_gi_ve_sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: function () {
                return (
                    '[Oggi a' +
                    (this.hours() > 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +
                    ']LT'
                );
            },
            nextDay: function () {
                return (
                    '[Domani a' +
                    (this.hours() > 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +
                    ']LT'
                );
            },
            nextWeek: function () {
                return (
                    'dddd [a' +
                    (this.hours() > 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +
                    ']LT'
                );
            },
            lastDay: function () {
                return (
                    '[Ieri a' +
                    (this.hours() > 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +
                    ']LT'
                );
            },
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                        return (
                            '[La scorsa] dddd [a' +
                            (this.hours() > 1
                                ? 'lle '
                                : this.hours() === 0
                                ? ' '
                                : "ll'") +
                            ']LT'
                        );
                    default:
                        return (
                            '[Lo scorso] dddd [a' +
                            (this.hours() > 1
                                ? 'lle '
                                : this.hours() === 0
                                ? ' '
                                : "ll'") +
                            ']LT'
                        );
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'tra %s',
            past: '%s fa',
            s: 'alcuni secondi',
            ss: '%d secondi',
            m: 'un minuto',
            mm: '%d minuti',
            h: "un'ora",
            hh: '%d ore',
            d: 'un giorno',
            dd: '%d giorni',
            w: 'una settimana',
            ww: '%d settimane',
            M: 'un mese',
            MM: '%d mesi',
            y: 'un anno',
            yy: '%d anni',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ja', {
        eras: [
            {
                since: '2019-05-01',
                offset: 1,
                name: '令和',
                narrow: '㋿',
                abbr: 'R',
            },
            {
                since: '1989-01-08',
                until: '2019-04-30',
                offset: 1,
                name: '平成',
                narrow: '㍻',
                abbr: 'H',
            },
            {
                since: '1926-12-25',
                until: '1989-01-07',
                offset: 1,
                name: '昭和',
                narrow: '㍼',
                abbr: 'S',
            },
            {
                since: '1912-07-30',
                until: '1926-12-24',
                offset: 1,
                name: '大正',
                narrow: '㍽',
                abbr: 'T',
            },
            {
                since: '1873-01-01',
                until: '1912-07-29',
                offset: 6,
                name: '明治',
                narrow: '㍾',
                abbr: 'M',
            },
            {
                since: '0001-01-01',
                until: '1873-12-31',
                offset: 1,
                name: '西暦',
                narrow: 'AD',
                abbr: 'AD',
            },
            {
                since: '0000-12-31',
                until: -Infinity,
                offset: 1,
                name: '紀元前',
                narrow: 'BC',
                abbr: 'BC',
            },
        ],
        eraYearOrdinalRegex: /(元|\d+)年/,
        eraYearOrdinalParse: function (input, match) {
            return match[1] === '元' ? 1 : parseInt(match[1] || input, 10);
        },
        months: '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split('_'),
        monthsShort: '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split(
            '_'
        ),
        weekdays: '日曜日_月曜日_火曜日_水曜日_木曜日_金曜日_土曜日'.split('_'),
        weekdaysShort: '日_月_火_水_木_金_土'.split('_'),
        weekdaysMin: '日_月_火_水_木_金_土'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY/MM/DD',
            LL: 'YYYY年M月D日',
            LLL: 'YYYY年M月D日 HH:mm',
            LLLL: 'YYYY年M月D日 dddd HH:mm',
            l: 'YYYY/MM/DD',
            ll: 'YYYY年M月D日',
            lll: 'YYYY年M月D日 HH:mm',
            llll: 'YYYY年M月D日(ddd) HH:mm',
        },
        meridiemParse: /午前|午後/i,
        isPM: function (input) {
            return input === '午後';
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return '午前';
            } else {
                return '午後';
            }
        },
        calendar: {
            sameDay: '[今日] LT',
            nextDay: '[明日] LT',
            nextWeek: function (now) {
                if (now.week() !== this.week()) {
                    return '[来週]dddd LT';
                } else {
                    return 'dddd LT';
                }
            },
            lastDay: '[昨日] LT',
            lastWeek: function (now) {
                if (this.week() !== now.week()) {
                    return '[先週]dddd LT';
                } else {
                    return 'dddd LT';
                }
            },
            sameElse: 'L',
        },
        dayOfMonthOrdinalParse: /\d{1,2}日/,
        ordinal: function (number, period) {
            switch (period) {
                case 'y':
                    return number === 1 ? '元年' : number + '年';
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '日';
                default:
                    return number;
            }
        },
        relativeTime: {
            future: '%s後',
            past: '%s前',
            s: '数秒',
            ss: '%d秒',
            m: '1分',
            mm: '%d分',
            h: '1時間',
            hh: '%d時間',
            d: '1日',
            dd: '%d日',
            M: '1ヶ月',
            MM: '%dヶ月',
            y: '1年',
            yy: '%d年',
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('jv', {
        months: 'Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_Nopember_Desember'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mar_Apr_Mei_Jun_Jul_Ags_Sep_Okt_Nop_Des'.split('_'),
        weekdays: 'Minggu_Senen_Seloso_Rebu_Kemis_Jemuwah_Septu'.split('_'),
        weekdaysShort: 'Min_Sen_Sel_Reb_Kem_Jem_Sep'.split('_'),
        weekdaysMin: 'Mg_Sn_Sl_Rb_Km_Jm_Sp'.split('_'),
        longDateFormat: {
            LT: 'HH.mm',
            LTS: 'HH.mm.ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY [pukul] HH.mm',
            LLLL: 'dddd, D MMMM YYYY [pukul] HH.mm',
        },
        meridiemParse: /enjing|siyang|sonten|ndalu/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'enjing') {
                return hour;
            } else if (meridiem === 'siyang') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'sonten' || meridiem === 'ndalu') {
                return hour + 12;
            }
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 11) {
                return 'enjing';
            } else if (hours < 15) {
                return 'siyang';
            } else if (hours < 19) {
                return 'sonten';
            } else {
                return 'ndalu';
            }
        },
        calendar: {
            sameDay: '[Dinten puniko pukul] LT',
            nextDay: '[Mbenjang pukul] LT',
            nextWeek: 'dddd [pukul] LT',
            lastDay: '[Kala wingi pukul] LT',
            lastWeek: 'dddd [kepengker pukul] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'wonten ing %s',
            past: '%s ingkang kepengker',
            s: 'sawetawis detik',
            ss: '%d detik',
            m: 'setunggal menit',
            mm: '%d menit',
            h: 'setunggal jam',
            hh: '%d jam',
            d: 'sedinten',
            dd: '%d dinten',
            M: 'sewulan',
            MM: '%d wulan',
            y: 'setaun',
            yy: '%d taun',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ka', {
        months: 'იანვარი_თებერვალი_მარტი_აპრილი_მაისი_ივნისი_ივლისი_აგვისტო_სექტემბერი_ოქტომბერი_ნოემბერი_დეკემბერი'.split(
            '_'
        ),
        monthsShort: 'იან_თებ_მარ_აპრ_მაი_ივნ_ივლ_აგვ_სექ_ოქტ_ნოე_დეკ'.split('_'),
        weekdays: {
            standalone:
                'კვირა_ორშაბათი_სამშაბათი_ოთხშაბათი_ხუთშაბათი_პარასკევი_შაბათი'.split(
                    '_'
                ),
            format: 'კვირას_ორშაბათს_სამშაბათს_ოთხშაბათს_ხუთშაბათს_პარასკევს_შაბათს'.split(
                '_'
            ),
            isFormat: /(წინა|შემდეგ)/,
        },
        weekdaysShort: 'კვი_ორშ_სამ_ოთხ_ხუთ_პარ_შაბ'.split('_'),
        weekdaysMin: 'კვ_ორ_სა_ოთ_ხუ_პა_შა'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[დღეს] LT[-ზე]',
            nextDay: '[ხვალ] LT[-ზე]',
            lastDay: '[გუშინ] LT[-ზე]',
            nextWeek: '[შემდეგ] dddd LT[-ზე]',
            lastWeek: '[წინა] dddd LT-ზე',
            sameElse: 'L',
        },
        relativeTime: {
            future: function (s) {
                return s.replace(
                    /(წამ|წუთ|საათ|წელ|დღ|თვ)(ი|ე)/,
                    function ($0, $1, $2) {
                        return $2 === 'ი' ? $1 + 'ში' : $1 + $2 + 'ში';
                    }
                );
            },
            past: function (s) {
                if (/(წამი|წუთი|საათი|დღე|თვე)/.test(s)) {
                    return s.replace(/(ი|ე)$/, 'ის წინ');
                }
                if (/წელი/.test(s)) {
                    return s.replace(/წელი$/, 'წლის წინ');
                }
                return s;
            },
            s: 'რამდენიმე წამი',
            ss: '%d წამი',
            m: 'წუთი',
            mm: '%d წუთი',
            h: 'საათი',
            hh: '%d საათი',
            d: 'დღე',
            dd: '%d დღე',
            M: 'თვე',
            MM: '%d თვე',
            y: 'წელი',
            yy: '%d წელი',
        },
        dayOfMonthOrdinalParse: /0|1-ლი|მე-\d{1,2}|\d{1,2}-ე/,
        ordinal: function (number) {
            if (number === 0) {
                return number;
            }
            if (number === 1) {
                return number + '-ლი';
            }
            if (
                number < 20 ||
                (number <= 100 && number % 20 === 0) ||
                number % 100 === 0
            ) {
                return 'მე-' + number;
            }
            return number + '-ე';
        },
        week: {
            dow: 1,
            doy: 7,
        },
    });

    //! moment.js locale configuration

    var suffixes$1 = {
        0: '-ші',
        1: '-ші',
        2: '-ші',
        3: '-ші',
        4: '-ші',
        5: '-ші',
        6: '-шы',
        7: '-ші',
        8: '-ші',
        9: '-шы',
        10: '-шы',
        20: '-шы',
        30: '-шы',
        40: '-шы',
        50: '-ші',
        60: '-шы',
        70: '-ші',
        80: '-ші',
        90: '-шы',
        100: '-ші',
    };

    hooks.defineLocale('kk', {
        months: 'қаңтар_ақпан_наурыз_сәуір_мамыр_маусым_шілде_тамыз_қыркүйек_қазан_қараша_желтоқсан'.split(
            '_'
        ),
        monthsShort: 'қаң_ақп_нау_сәу_мам_мау_шіл_там_қыр_қаз_қар_жел'.split('_'),
        weekdays: 'жексенбі_дүйсенбі_сейсенбі_сәрсенбі_бейсенбі_жұма_сенбі'.split(
            '_'
        ),
        weekdaysShort: 'жек_дүй_сей_сәр_бей_жұм_сен'.split('_'),
        weekdaysMin: 'жк_дй_сй_ср_бй_жм_сн'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Бүгін сағат] LT',
            nextDay: '[Ертең сағат] LT',
            nextWeek: 'dddd [сағат] LT',
            lastDay: '[Кеше сағат] LT',
            lastWeek: '[Өткен аптаның] dddd [сағат] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s ішінде',
            past: '%s бұрын',
            s: 'бірнеше секунд',
            ss: '%d секунд',
            m: 'бір минут',
            mm: '%d минут',
            h: 'бір сағат',
            hh: '%d сағат',
            d: 'бір күн',
            dd: '%d күн',
            M: 'бір ай',
            MM: '%d ай',
            y: 'бір жыл',
            yy: '%d жыл',
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(ші|шы)/,
        ordinal: function (number) {
            var a = number % 10,
                b = number >= 100 ? 100 : null;
            return number + (suffixes$1[number] || suffixes$1[a] || suffixes$1[b]);
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$9 = {
            1: '១',
            2: '២',
            3: '៣',
            4: '៤',
            5: '៥',
            6: '៦',
            7: '៧',
            8: '៨',
            9: '៩',
            0: '០',
        },
        numberMap$8 = {
            '១': '1',
            '២': '2',
            '៣': '3',
            '៤': '4',
            '៥': '5',
            '៦': '6',
            '៧': '7',
            '៨': '8',
            '៩': '9',
            '០': '0',
        };

    hooks.defineLocale('km', {
        months: 'មករា_កុម្ភៈ_មីនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ'.split(
            '_'
        ),
        monthsShort:
            'មករា_កុម្ភៈ_មីនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ'.split(
                '_'
            ),
        weekdays: 'អាទិត្យ_ច័ន្ទ_អង្គារ_ពុធ_ព្រហស្បតិ៍_សុក្រ_សៅរ៍'.split('_'),
        weekdaysShort: 'អា_ច_អ_ព_ព្រ_សុ_ស'.split('_'),
        weekdaysMin: 'អា_ច_អ_ព_ព្រ_សុ_ស'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        meridiemParse: /ព្រឹក|ល្ងាច/,
        isPM: function (input) {
            return input === 'ល្ងាច';
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ព្រឹក';
            } else {
                return 'ល្ងាច';
            }
        },
        calendar: {
            sameDay: '[ថ្ងៃនេះ ម៉ោង] LT',
            nextDay: '[ស្អែក ម៉ោង] LT',
            nextWeek: 'dddd [ម៉ោង] LT',
            lastDay: '[ម្សិលមិញ ម៉ោង] LT',
            lastWeek: 'dddd [សប្តាហ៍មុន] [ម៉ោង] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%sទៀត',
            past: '%sមុន',
            s: 'ប៉ុន្មានវិនាទី',
            ss: '%d វិនាទី',
            m: 'មួយនាទី',
            mm: '%d នាទី',
            h: 'មួយម៉ោង',
            hh: '%d ម៉ោង',
            d: 'មួយថ្ងៃ',
            dd: '%d ថ្ងៃ',
            M: 'មួយខែ',
            MM: '%d ខែ',
            y: 'មួយឆ្នាំ',
            yy: '%d ឆ្នាំ',
        },
        dayOfMonthOrdinalParse: /ទី\d{1,2}/,
        ordinal: 'ទី%d',
        preparse: function (string) {
            return string.replace(/[១២៣៤៥៦៧៨៩០]/g, function (match) {
                return numberMap$8[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$9[match];
            });
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$a = {
            1: '೧',
            2: '೨',
            3: '೩',
            4: '೪',
            5: '೫',
            6: '೬',
            7: '೭',
            8: '೮',
            9: '೯',
            0: '೦',
        },
        numberMap$9 = {
            '೧': '1',
            '೨': '2',
            '೩': '3',
            '೪': '4',
            '೫': '5',
            '೬': '6',
            '೭': '7',
            '೮': '8',
            '೯': '9',
            '೦': '0',
        };

    hooks.defineLocale('kn', {
        months: 'ಜನವರಿ_ಫೆಬ್ರವರಿ_ಮಾರ್ಚ್_ಏಪ್ರಿಲ್_ಮೇ_ಜೂನ್_ಜುಲೈ_ಆಗಸ್ಟ್_ಸೆಪ್ಟೆಂಬರ್_ಅಕ್ಟೋಬರ್_ನವೆಂಬರ್_ಡಿಸೆಂಬರ್'.split(
            '_'
        ),
        monthsShort:
            'ಜನ_ಫೆಬ್ರ_ಮಾರ್ಚ್_ಏಪ್ರಿಲ್_ಮೇ_ಜೂನ್_ಜುಲೈ_ಆಗಸ್ಟ್_ಸೆಪ್ಟೆಂ_ಅಕ್ಟೋ_ನವೆಂ_ಡಿಸೆಂ'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'ಭಾನುವಾರ_ಸೋಮವಾರ_ಮಂಗಳವಾರ_ಬುಧವಾರ_ಗುರುವಾರ_ಶುಕ್ರವಾರ_ಶನಿವಾರ'.split(
            '_'
        ),
        weekdaysShort: 'ಭಾನು_ಸೋಮ_ಮಂಗಳ_ಬುಧ_ಗುರು_ಶುಕ್ರ_ಶನಿ'.split('_'),
        weekdaysMin: 'ಭಾ_ಸೋ_ಮಂ_ಬು_ಗು_ಶು_ಶ'.split('_'),
        longDateFormat: {
            LT: 'A h:mm',
            LTS: 'A h:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm',
            LLLL: 'dddd, D MMMM YYYY, A h:mm',
        },
        calendar: {
            sameDay: '[ಇಂದು] LT',
            nextDay: '[ನಾಳೆ] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[ನಿನ್ನೆ] LT',
            lastWeek: '[ಕೊನೆಯ] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s ನಂತರ',
            past: '%s ಹಿಂದೆ',
            s: 'ಕೆಲವು ಕ್ಷಣಗಳು',
            ss: '%d ಸೆಕೆಂಡುಗಳು',
            m: 'ಒಂದು ನಿಮಿಷ',
            mm: '%d ನಿಮಿಷ',
            h: 'ಒಂದು ಗಂಟೆ',
            hh: '%d ಗಂಟೆ',
            d: 'ಒಂದು ದಿನ',
            dd: '%d ದಿನ',
            M: 'ಒಂದು ತಿಂಗಳು',
            MM: '%d ತಿಂಗಳು',
            y: 'ಒಂದು ವರ್ಷ',
            yy: '%d ವರ್ಷ',
        },
        preparse: function (string) {
            return string.replace(/[೧೨೩೪೫೬೭೮೯೦]/g, function (match) {
                return numberMap$9[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$a[match];
            });
        },
        meridiemParse: /ರಾತ್ರಿ|ಬೆಳಿಗ್ಗೆ|ಮಧ್ಯಾಹ್ನ|ಸಂಜೆ/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'ರಾತ್ರಿ') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'ಬೆಳಿಗ್ಗೆ') {
                return hour;
            } else if (meridiem === 'ಮಧ್ಯಾಹ್ನ') {
                return hour >= 10 ? hour : hour + 12;
            } else if (meridiem === 'ಸಂಜೆ') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'ರಾತ್ರಿ';
            } else if (hour < 10) {
                return 'ಬೆಳಿಗ್ಗೆ';
            } else if (hour < 17) {
                return 'ಮಧ್ಯಾಹ್ನ';
            } else if (hour < 20) {
                return 'ಸಂಜೆ';
            } else {
                return 'ರಾತ್ರಿ';
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}(ನೇ)/,
        ordinal: function (number) {
            return number + 'ನೇ';
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ko', {
        months: '1월_2월_3월_4월_5월_6월_7월_8월_9월_10월_11월_12월'.split('_'),
        monthsShort: '1월_2월_3월_4월_5월_6월_7월_8월_9월_10월_11월_12월'.split(
            '_'
        ),
        weekdays: '일요일_월요일_화요일_수요일_목요일_금요일_토요일'.split('_'),
        weekdaysShort: '일_월_화_수_목_금_토'.split('_'),
        weekdaysMin: '일_월_화_수_목_금_토'.split('_'),
        longDateFormat: {
            LT: 'A h:mm',
            LTS: 'A h:mm:ss',
            L: 'YYYY.MM.DD.',
            LL: 'YYYY년 MMMM D일',
            LLL: 'YYYY년 MMMM D일 A h:mm',
            LLLL: 'YYYY년 MMMM D일 dddd A h:mm',
            l: 'YYYY.MM.DD.',
            ll: 'YYYY년 MMMM D일',
            lll: 'YYYY년 MMMM D일 A h:mm',
            llll: 'YYYY년 MMMM D일 dddd A h:mm',
        },
        calendar: {
            sameDay: '오늘 LT',
            nextDay: '내일 LT',
            nextWeek: 'dddd LT',
            lastDay: '어제 LT',
            lastWeek: '지난주 dddd LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s 후',
            past: '%s 전',
            s: '몇 초',
            ss: '%d초',
            m: '1분',
            mm: '%d분',
            h: '한 시간',
            hh: '%d시간',
            d: '하루',
            dd: '%d일',
            M: '한 달',
            MM: '%d달',
            y: '일 년',
            yy: '%d년',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(일|월|주)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '일';
                case 'M':
                    return number + '월';
                case 'w':
                case 'W':
                    return number + '주';
                default:
                    return number;
            }
        },
        meridiemParse: /오전|오후/,
        isPM: function (token) {
            return token === '오후';
        },
        meridiem: function (hour, minute, isUpper) {
            return hour < 12 ? '오전' : '오후';
        },
    });

    //! moment.js locale configuration

    var symbolMap$b = {
            1: '١',
            2: '٢',
            3: '٣',
            4: '٤',
            5: '٥',
            6: '٦',
            7: '٧',
            8: '٨',
            9: '٩',
            0: '٠',
        },
        numberMap$a = {
            '١': '1',
            '٢': '2',
            '٣': '3',
            '٤': '4',
            '٥': '5',
            '٦': '6',
            '٧': '7',
            '٨': '8',
            '٩': '9',
            '٠': '0',
        },
        months$8 = [
            'کانونی دووەم',
            'شوبات',
            'ئازار',
            'نیسان',
            'ئایار',
            'حوزەیران',
            'تەمموز',
            'ئاب',
            'ئەیلوول',
            'تشرینی یەكەم',
            'تشرینی دووەم',
            'كانونی یەکەم',
        ];

    hooks.defineLocale('ku', {
        months: months$8,
        monthsShort: months$8,
        weekdays:
            'یه‌كشه‌ممه‌_دووشه‌ممه‌_سێشه‌ممه‌_چوارشه‌ممه‌_پێنجشه‌ممه‌_هه‌ینی_شه‌ممه‌'.split(
                '_'
            ),
        weekdaysShort:
            'یه‌كشه‌م_دووشه‌م_سێشه‌م_چوارشه‌م_پێنجشه‌م_هه‌ینی_شه‌ممه‌'.split('_'),
        weekdaysMin: 'ی_د_س_چ_پ_ه_ش'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        meridiemParse: /ئێواره‌|به‌یانی/,
        isPM: function (input) {
            return /ئێواره‌/.test(input);
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'به‌یانی';
            } else {
                return 'ئێواره‌';
            }
        },
        calendar: {
            sameDay: '[ئه‌مرۆ كاتژمێر] LT',
            nextDay: '[به‌یانی كاتژمێر] LT',
            nextWeek: 'dddd [كاتژمێر] LT',
            lastDay: '[دوێنێ كاتژمێر] LT',
            lastWeek: 'dddd [كاتژمێر] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'له‌ %s',
            past: '%s',
            s: 'چه‌ند چركه‌یه‌ك',
            ss: 'چركه‌ %d',
            m: 'یه‌ك خوله‌ك',
            mm: '%d خوله‌ك',
            h: 'یه‌ك كاتژمێر',
            hh: '%d كاتژمێر',
            d: 'یه‌ك ڕۆژ',
            dd: '%d ڕۆژ',
            M: 'یه‌ك مانگ',
            MM: '%d مانگ',
            y: 'یه‌ك ساڵ',
            yy: '%d ساڵ',
        },
        preparse: function (string) {
            return string
                .replace(/[١٢٣٤٥٦٧٨٩٠]/g, function (match) {
                    return numberMap$a[match];
                })
                .replace(/،/g, ',');
        },
        postformat: function (string) {
            return string
                .replace(/\d/g, function (match) {
                    return symbolMap$b[match];
                })
                .replace(/,/g, '،');
        },
        week: {
            dow: 6, // Saturday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var suffixes$2 = {
        0: '-чү',
        1: '-чи',
        2: '-чи',
        3: '-чү',
        4: '-чү',
        5: '-чи',
        6: '-чы',
        7: '-чи',
        8: '-чи',
        9: '-чу',
        10: '-чу',
        20: '-чы',
        30: '-чу',
        40: '-чы',
        50: '-чү',
        60: '-чы',
        70: '-чи',
        80: '-чи',
        90: '-чу',
        100: '-чү',
    };

    hooks.defineLocale('ky', {
        months: 'январь_февраль_март_апрель_май_июнь_июль_август_сентябрь_октябрь_ноябрь_декабрь'.split(
            '_'
        ),
        monthsShort: 'янв_фев_март_апр_май_июнь_июль_авг_сен_окт_ноя_дек'.split(
            '_'
        ),
        weekdays: 'Жекшемби_Дүйшөмбү_Шейшемби_Шаршемби_Бейшемби_Жума_Ишемби'.split(
            '_'
        ),
        weekdaysShort: 'Жек_Дүй_Шей_Шар_Бей_Жум_Ише'.split('_'),
        weekdaysMin: 'Жк_Дй_Шй_Шр_Бй_Жм_Иш'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Бүгүн саат] LT',
            nextDay: '[Эртең саат] LT',
            nextWeek: 'dddd [саат] LT',
            lastDay: '[Кечээ саат] LT',
            lastWeek: '[Өткөн аптанын] dddd [күнү] [саат] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s ичинде',
            past: '%s мурун',
            s: 'бирнече секунд',
            ss: '%d секунд',
            m: 'бир мүнөт',
            mm: '%d мүнөт',
            h: 'бир саат',
            hh: '%d саат',
            d: 'бир күн',
            dd: '%d күн',
            M: 'бир ай',
            MM: '%d ай',
            y: 'бир жыл',
            yy: '%d жыл',
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(чи|чы|чү|чу)/,
        ordinal: function (number) {
            var a = number % 10,
                b = number >= 100 ? 100 : null;
            return number + (suffixes$2[number] || suffixes$2[a] || suffixes$2[b]);
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function processRelativeTime$6(number, withoutSuffix, key, isFuture) {
        var format = {
            m: ['eng Minutt', 'enger Minutt'],
            h: ['eng Stonn', 'enger Stonn'],
            d: ['een Dag', 'engem Dag'],
            M: ['ee Mount', 'engem Mount'],
            y: ['ee Joer', 'engem Joer'],
        };
        return withoutSuffix ? format[key][0] : format[key][1];
    }
    function processFutureTime(string) {
        var number = string.substr(0, string.indexOf(' '));
        if (eifelerRegelAppliesToNumber(number)) {
            return 'a ' + string;
        }
        return 'an ' + string;
    }
    function processPastTime(string) {
        var number = string.substr(0, string.indexOf(' '));
        if (eifelerRegelAppliesToNumber(number)) {
            return 'viru ' + string;
        }
        return 'virun ' + string;
    }
    /**
     * Returns true if the word before the given number loses the '-n' ending.
     * e.g. 'an 10 Deeg' but 'a 5 Deeg'
     *
     * @param number {integer}
     * @returns {boolean}
     */
    function eifelerRegelAppliesToNumber(number) {
        number = parseInt(number, 10);
        if (isNaN(number)) {
            return false;
        }
        if (number < 0) {
            // Negative Number --> always true
            return true;
        } else if (number < 10) {
            // Only 1 digit
            if (4 <= number && number <= 7) {
                return true;
            }
            return false;
        } else if (number < 100) {
            // 2 digits
            var lastDigit = number % 10,
                firstDigit = number / 10;
            if (lastDigit === 0) {
                return eifelerRegelAppliesToNumber(firstDigit);
            }
            return eifelerRegelAppliesToNumber(lastDigit);
        } else if (number < 10000) {
            // 3 or 4 digits --> recursively check first digit
            while (number >= 10) {
                number = number / 10;
            }
            return eifelerRegelAppliesToNumber(number);
        } else {
            // Anything larger than 4 digits: recursively check first n-3 digits
            number = number / 1000;
            return eifelerRegelAppliesToNumber(number);
        }
    }

    hooks.defineLocale('lb', {
        months: 'Januar_Februar_Mäerz_Abrëll_Mee_Juni_Juli_August_September_Oktober_November_Dezember'.split(
            '_'
        ),
        monthsShort:
            'Jan._Febr._Mrz._Abr._Mee_Jun._Jul._Aug._Sept._Okt._Nov._Dez.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays:
            'Sonndeg_Méindeg_Dënschdeg_Mëttwoch_Donneschdeg_Freideg_Samschdeg'.split(
                '_'
            ),
        weekdaysShort: 'So._Mé._Dë._Më._Do._Fr._Sa.'.split('_'),
        weekdaysMin: 'So_Mé_Dë_Më_Do_Fr_Sa'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm [Auer]',
            LTS: 'H:mm:ss [Auer]',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY H:mm [Auer]',
            LLLL: 'dddd, D. MMMM YYYY H:mm [Auer]',
        },
        calendar: {
            sameDay: '[Haut um] LT',
            sameElse: 'L',
            nextDay: '[Muer um] LT',
            nextWeek: 'dddd [um] LT',
            lastDay: '[Gëschter um] LT',
            lastWeek: function () {
                // Different date string for 'Dënschdeg' (Tuesday) and 'Donneschdeg' (Thursday) due to phonological rule
                switch (this.day()) {
                    case 2:
                    case 4:
                        return '[Leschten] dddd [um] LT';
                    default:
                        return '[Leschte] dddd [um] LT';
                }
            },
        },
        relativeTime: {
            future: processFutureTime,
            past: processPastTime,
            s: 'e puer Sekonnen',
            ss: '%d Sekonnen',
            m: processRelativeTime$6,
            mm: '%d Minutten',
            h: processRelativeTime$6,
            hh: '%d Stonnen',
            d: processRelativeTime$6,
            dd: '%d Deeg',
            M: processRelativeTime$6,
            MM: '%d Méint',
            y: processRelativeTime$6,
            yy: '%d Joer',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('lo', {
        months: 'ມັງກອນ_ກຸມພາ_ມີນາ_ເມສາ_ພຶດສະພາ_ມິຖຸນາ_ກໍລະກົດ_ສິງຫາ_ກັນຍາ_ຕຸລາ_ພະຈິກ_ທັນວາ'.split(
            '_'
        ),
        monthsShort:
            'ມັງກອນ_ກຸມພາ_ມີນາ_ເມສາ_ພຶດສະພາ_ມິຖຸນາ_ກໍລະກົດ_ສິງຫາ_ກັນຍາ_ຕຸລາ_ພະຈິກ_ທັນວາ'.split(
                '_'
            ),
        weekdays: 'ອາທິດ_ຈັນ_ອັງຄານ_ພຸດ_ພະຫັດ_ສຸກ_ເສົາ'.split('_'),
        weekdaysShort: 'ທິດ_ຈັນ_ອັງຄານ_ພຸດ_ພະຫັດ_ສຸກ_ເສົາ'.split('_'),
        weekdaysMin: 'ທ_ຈ_ອຄ_ພ_ພຫ_ສກ_ສ'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'ວັນdddd D MMMM YYYY HH:mm',
        },
        meridiemParse: /ຕອນເຊົ້າ|ຕອນແລງ/,
        isPM: function (input) {
            return input === 'ຕອນແລງ';
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ຕອນເຊົ້າ';
            } else {
                return 'ຕອນແລງ';
            }
        },
        calendar: {
            sameDay: '[ມື້ນີ້ເວລາ] LT',
            nextDay: '[ມື້ອື່ນເວລາ] LT',
            nextWeek: '[ວັນ]dddd[ໜ້າເວລາ] LT',
            lastDay: '[ມື້ວານນີ້ເວລາ] LT',
            lastWeek: '[ວັນ]dddd[ແລ້ວນີ້ເວລາ] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'ອີກ %s',
            past: '%sຜ່ານມາ',
            s: 'ບໍ່ເທົ່າໃດວິນາທີ',
            ss: '%d ວິນາທີ',
            m: '1 ນາທີ',
            mm: '%d ນາທີ',
            h: '1 ຊົ່ວໂມງ',
            hh: '%d ຊົ່ວໂມງ',
            d: '1 ມື້',
            dd: '%d ມື້',
            M: '1 ເດືອນ',
            MM: '%d ເດືອນ',
            y: '1 ປີ',
            yy: '%d ປີ',
        },
        dayOfMonthOrdinalParse: /(ທີ່)\d{1,2}/,
        ordinal: function (number) {
            return 'ທີ່' + number;
        },
    });

    //! moment.js locale configuration

    var units = {
        ss: 'sekundė_sekundžių_sekundes',
        m: 'minutė_minutės_minutę',
        mm: 'minutės_minučių_minutes',
        h: 'valanda_valandos_valandą',
        hh: 'valandos_valandų_valandas',
        d: 'diena_dienos_dieną',
        dd: 'dienos_dienų_dienas',
        M: 'mėnuo_mėnesio_mėnesį',
        MM: 'mėnesiai_mėnesių_mėnesius',
        y: 'metai_metų_metus',
        yy: 'metai_metų_metus',
    };
    function translateSeconds(number, withoutSuffix, key, isFuture) {
        if (withoutSuffix) {
            return 'kelios sekundės';
        } else {
            return isFuture ? 'kelių sekundžių' : 'kelias sekundes';
        }
    }
    function translateSingular(number, withoutSuffix, key, isFuture) {
        return withoutSuffix
            ? forms(key)[0]
            : isFuture
            ? forms(key)[1]
            : forms(key)[2];
    }
    function special(number) {
        return number % 10 === 0 || (number > 10 && number < 20);
    }
    function forms(key) {
        return units[key].split('_');
    }
    function translate$6(number, withoutSuffix, key, isFuture) {
        var result = number + ' ';
        if (number === 1) {
            return (
                result + translateSingular(number, withoutSuffix, key[0], isFuture)
            );
        } else if (withoutSuffix) {
            return result + (special(number) ? forms(key)[1] : forms(key)[0]);
        } else {
            if (isFuture) {
                return result + forms(key)[1];
            } else {
                return result + (special(number) ? forms(key)[1] : forms(key)[2]);
            }
        }
    }
    hooks.defineLocale('lt', {
        months: {
            format: 'sausio_vasario_kovo_balandžio_gegužės_birželio_liepos_rugpjūčio_rugsėjo_spalio_lapkričio_gruodžio'.split(
                '_'
            ),
            standalone:
                'sausis_vasaris_kovas_balandis_gegužė_birželis_liepa_rugpjūtis_rugsėjis_spalis_lapkritis_gruodis'.split(
                    '_'
                ),
            isFormat: /D[oD]?(\[[^\[\]]*\]|\s)+MMMM?|MMMM?(\[[^\[\]]*\]|\s)+D[oD]?/,
        },
        monthsShort: 'sau_vas_kov_bal_geg_bir_lie_rgp_rgs_spa_lap_grd'.split('_'),
        weekdays: {
            format: 'sekmadienį_pirmadienį_antradienį_trečiadienį_ketvirtadienį_penktadienį_šeštadienį'.split(
                '_'
            ),
            standalone:
                'sekmadienis_pirmadienis_antradienis_trečiadienis_ketvirtadienis_penktadienis_šeštadienis'.split(
                    '_'
                ),
            isFormat: /dddd HH:mm/,
        },
        weekdaysShort: 'Sek_Pir_Ant_Tre_Ket_Pen_Šeš'.split('_'),
        weekdaysMin: 'S_P_A_T_K_Pn_Š'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY-MM-DD',
            LL: 'YYYY [m.] MMMM D [d.]',
            LLL: 'YYYY [m.] MMMM D [d.], HH:mm [val.]',
            LLLL: 'YYYY [m.] MMMM D [d.], dddd, HH:mm [val.]',
            l: 'YYYY-MM-DD',
            ll: 'YYYY [m.] MMMM D [d.]',
            lll: 'YYYY [m.] MMMM D [d.], HH:mm [val.]',
            llll: 'YYYY [m.] MMMM D [d.], ddd, HH:mm [val.]',
        },
        calendar: {
            sameDay: '[Šiandien] LT',
            nextDay: '[Rytoj] LT',
            nextWeek: 'dddd LT',
            lastDay: '[Vakar] LT',
            lastWeek: '[Praėjusį] dddd LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'po %s',
            past: 'prieš %s',
            s: translateSeconds,
            ss: translate$6,
            m: translateSingular,
            mm: translate$6,
            h: translateSingular,
            hh: translate$6,
            d: translateSingular,
            dd: translate$6,
            M: translateSingular,
            MM: translate$6,
            y: translateSingular,
            yy: translate$6,
        },
        dayOfMonthOrdinalParse: /\d{1,2}-oji/,
        ordinal: function (number) {
            return number + '-oji';
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var units$1 = {
        ss: 'sekundes_sekundēm_sekunde_sekundes'.split('_'),
        m: 'minūtes_minūtēm_minūte_minūtes'.split('_'),
        mm: 'minūtes_minūtēm_minūte_minūtes'.split('_'),
        h: 'stundas_stundām_stunda_stundas'.split('_'),
        hh: 'stundas_stundām_stunda_stundas'.split('_'),
        d: 'dienas_dienām_diena_dienas'.split('_'),
        dd: 'dienas_dienām_diena_dienas'.split('_'),
        M: 'mēneša_mēnešiem_mēnesis_mēneši'.split('_'),
        MM: 'mēneša_mēnešiem_mēnesis_mēneši'.split('_'),
        y: 'gada_gadiem_gads_gadi'.split('_'),
        yy: 'gada_gadiem_gads_gadi'.split('_'),
    };
    /**
     * @param withoutSuffix boolean true = a length of time; false = before/after a period of time.
     */
    function format$1(forms, number, withoutSuffix) {
        if (withoutSuffix) {
            // E.g. "21 minūte", "3 minūtes".
            return number % 10 === 1 && number % 100 !== 11 ? forms[2] : forms[3];
        } else {
            // E.g. "21 minūtes" as in "pēc 21 minūtes".
            // E.g. "3 minūtēm" as in "pēc 3 minūtēm".
            return number % 10 === 1 && number % 100 !== 11 ? forms[0] : forms[1];
        }
    }
    function relativeTimeWithPlural$1(number, withoutSuffix, key) {
        return number + ' ' + format$1(units$1[key], number, withoutSuffix);
    }
    function relativeTimeWithSingular(number, withoutSuffix, key) {
        return format$1(units$1[key], number, withoutSuffix);
    }
    function relativeSeconds(number, withoutSuffix) {
        return withoutSuffix ? 'dažas sekundes' : 'dažām sekundēm';
    }

    hooks.defineLocale('lv', {
        months: 'janvāris_februāris_marts_aprīlis_maijs_jūnijs_jūlijs_augusts_septembris_oktobris_novembris_decembris'.split(
            '_'
        ),
        monthsShort: 'jan_feb_mar_apr_mai_jūn_jūl_aug_sep_okt_nov_dec'.split('_'),
        weekdays:
            'svētdiena_pirmdiena_otrdiena_trešdiena_ceturtdiena_piektdiena_sestdiena'.split(
                '_'
            ),
        weekdaysShort: 'Sv_P_O_T_C_Pk_S'.split('_'),
        weekdaysMin: 'Sv_P_O_T_C_Pk_S'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY.',
            LL: 'YYYY. [gada] D. MMMM',
            LLL: 'YYYY. [gada] D. MMMM, HH:mm',
            LLLL: 'YYYY. [gada] D. MMMM, dddd, HH:mm',
        },
        calendar: {
            sameDay: '[Šodien pulksten] LT',
            nextDay: '[Rīt pulksten] LT',
            nextWeek: 'dddd [pulksten] LT',
            lastDay: '[Vakar pulksten] LT',
            lastWeek: '[Pagājušā] dddd [pulksten] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'pēc %s',
            past: 'pirms %s',
            s: relativeSeconds,
            ss: relativeTimeWithPlural$1,
            m: relativeTimeWithSingular,
            mm: relativeTimeWithPlural$1,
            h: relativeTimeWithSingular,
            hh: relativeTimeWithPlural$1,
            d: relativeTimeWithSingular,
            dd: relativeTimeWithPlural$1,
            M: relativeTimeWithSingular,
            MM: relativeTimeWithPlural$1,
            y: relativeTimeWithSingular,
            yy: relativeTimeWithPlural$1,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var translator = {
        words: {
            //Different grammatical cases
            ss: ['sekund', 'sekunda', 'sekundi'],
            m: ['jedan minut', 'jednog minuta'],
            mm: ['minut', 'minuta', 'minuta'],
            h: ['jedan sat', 'jednog sata'],
            hh: ['sat', 'sata', 'sati'],
            dd: ['dan', 'dana', 'dana'],
            MM: ['mjesec', 'mjeseca', 'mjeseci'],
            yy: ['godina', 'godine', 'godina'],
        },
        correctGrammaticalCase: function (number, wordKey) {
            return number === 1
                ? wordKey[0]
                : number >= 2 && number <= 4
                ? wordKey[1]
                : wordKey[2];
        },
        translate: function (number, withoutSuffix, key) {
            var wordKey = translator.words[key];
            if (key.length === 1) {
                return withoutSuffix ? wordKey[0] : wordKey[1];
            } else {
                return (
                    number +
                    ' ' +
                    translator.correctGrammaticalCase(number, wordKey)
                );
            }
        },
    };

    hooks.defineLocale('me', {
        months: 'januar_februar_mart_april_maj_jun_jul_avgust_septembar_oktobar_novembar_decembar'.split(
            '_'
        ),
        monthsShort:
            'jan._feb._mar._apr._maj_jun_jul_avg._sep._okt._nov._dec.'.split('_'),
        monthsParseExact: true,
        weekdays: 'nedjelja_ponedjeljak_utorak_srijeda_četvrtak_petak_subota'.split(
            '_'
        ),
        weekdaysShort: 'ned._pon._uto._sri._čet._pet._sub.'.split('_'),
        weekdaysMin: 'ne_po_ut_sr_če_pe_su'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY H:mm',
            LLLL: 'dddd, D. MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[danas u] LT',
            nextDay: '[sjutra u] LT',

            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[u] [nedjelju] [u] LT';
                    case 3:
                        return '[u] [srijedu] [u] LT';
                    case 6:
                        return '[u] [subotu] [u] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[u] dddd [u] LT';
                }
            },
            lastDay: '[juče u] LT',
            lastWeek: function () {
                var lastWeekDays = [
                    '[prošle] [nedjelje] [u] LT',
                    '[prošlog] [ponedjeljka] [u] LT',
                    '[prošlog] [utorka] [u] LT',
                    '[prošle] [srijede] [u] LT',
                    '[prošlog] [četvrtka] [u] LT',
                    '[prošlog] [petka] [u] LT',
                    '[prošle] [subote] [u] LT',
                ];
                return lastWeekDays[this.day()];
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'za %s',
            past: 'prije %s',
            s: 'nekoliko sekundi',
            ss: translator.translate,
            m: translator.translate,
            mm: translator.translate,
            h: translator.translate,
            hh: translator.translate,
            d: 'dan',
            dd: translator.translate,
            M: 'mjesec',
            MM: translator.translate,
            y: 'godinu',
            yy: translator.translate,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('mi', {
        months: 'Kohi-tāte_Hui-tanguru_Poutū-te-rangi_Paenga-whāwhā_Haratua_Pipiri_Hōngoingoi_Here-turi-kōkā_Mahuru_Whiringa-ā-nuku_Whiringa-ā-rangi_Hakihea'.split(
            '_'
        ),
        monthsShort:
            'Kohi_Hui_Pou_Pae_Hara_Pipi_Hōngoi_Here_Mahu_Whi-nu_Whi-ra_Haki'.split(
                '_'
            ),
        monthsRegex: /(?:['a-z\u0101\u014D\u016B]+\-?){1,3}/i,
        monthsStrictRegex: /(?:['a-z\u0101\u014D\u016B]+\-?){1,3}/i,
        monthsShortRegex: /(?:['a-z\u0101\u014D\u016B]+\-?){1,3}/i,
        monthsShortStrictRegex: /(?:['a-z\u0101\u014D\u016B]+\-?){1,2}/i,
        weekdays: 'Rātapu_Mane_Tūrei_Wenerei_Tāite_Paraire_Hātarei'.split('_'),
        weekdaysShort: 'Ta_Ma_Tū_We_Tāi_Pa_Hā'.split('_'),
        weekdaysMin: 'Ta_Ma_Tū_We_Tāi_Pa_Hā'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY [i] HH:mm',
            LLLL: 'dddd, D MMMM YYYY [i] HH:mm',
        },
        calendar: {
            sameDay: '[i teie mahana, i] LT',
            nextDay: '[apopo i] LT',
            nextWeek: 'dddd [i] LT',
            lastDay: '[inanahi i] LT',
            lastWeek: 'dddd [whakamutunga i] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'i roto i %s',
            past: '%s i mua',
            s: 'te hēkona ruarua',
            ss: '%d hēkona',
            m: 'he meneti',
            mm: '%d meneti',
            h: 'te haora',
            hh: '%d haora',
            d: 'he ra',
            dd: '%d ra',
            M: 'he marama',
            MM: '%d marama',
            y: 'he tau',
            yy: '%d tau',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('mk', {
        months: 'јануари_февруари_март_април_мај_јуни_јули_август_септември_октомври_ноември_декември'.split(
            '_'
        ),
        monthsShort: 'јан_фев_мар_апр_мај_јун_јул_авг_сеп_окт_ное_дек'.split('_'),
        weekdays: 'недела_понеделник_вторник_среда_четврток_петок_сабота'.split(
            '_'
        ),
        weekdaysShort: 'нед_пон_вто_сре_чет_пет_саб'.split('_'),
        weekdaysMin: 'нe_пo_вт_ср_че_пе_сa'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'D.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY H:mm',
            LLLL: 'dddd, D MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[Денес во] LT',
            nextDay: '[Утре во] LT',
            nextWeek: '[Во] dddd [во] LT',
            lastDay: '[Вчера во] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                    case 3:
                    case 6:
                        return '[Изминатата] dddd [во] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[Изминатиот] dddd [во] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'за %s',
            past: 'пред %s',
            s: 'неколку секунди',
            ss: '%d секунди',
            m: 'една минута',
            mm: '%d минути',
            h: 'еден час',
            hh: '%d часа',
            d: 'еден ден',
            dd: '%d дена',
            M: 'еден месец',
            MM: '%d месеци',
            y: 'една година',
            yy: '%d години',
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(ев|ен|ти|ви|ри|ми)/,
        ordinal: function (number) {
            var lastDigit = number % 10,
                last2Digits = number % 100;
            if (number === 0) {
                return number + '-ев';
            } else if (last2Digits === 0) {
                return number + '-ен';
            } else if (last2Digits > 10 && last2Digits < 20) {
                return number + '-ти';
            } else if (lastDigit === 1) {
                return number + '-ви';
            } else if (lastDigit === 2) {
                return number + '-ри';
            } else if (lastDigit === 7 || lastDigit === 8) {
                return number + '-ми';
            } else {
                return number + '-ти';
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ml', {
        months: 'ജനുവരി_ഫെബ്രുവരി_മാർച്ച്_ഏപ്രിൽ_മേയ്_ജൂൺ_ജൂലൈ_ഓഗസ്റ്റ്_സെപ്റ്റംബർ_ഒക്ടോബർ_നവംബർ_ഡിസംബർ'.split(
            '_'
        ),
        monthsShort:
            'ജനു._ഫെബ്രു._മാർ._ഏപ്രി._മേയ്_ജൂൺ_ജൂലൈ._ഓഗ._സെപ്റ്റ._ഒക്ടോ._നവം._ഡിസം.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays:
            'ഞായറാഴ്ച_തിങ്കളാഴ്ച_ചൊവ്വാഴ്ച_ബുധനാഴ്ച_വ്യാഴാഴ്ച_വെള്ളിയാഴ്ച_ശനിയാഴ്ച'.split(
                '_'
            ),
        weekdaysShort: 'ഞായർ_തിങ്കൾ_ചൊവ്വ_ബുധൻ_വ്യാഴം_വെള്ളി_ശനി'.split('_'),
        weekdaysMin: 'ഞാ_തി_ചൊ_ബു_വ്യാ_വെ_ശ'.split('_'),
        longDateFormat: {
            LT: 'A h:mm -നു',
            LTS: 'A h:mm:ss -നു',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm -നു',
            LLLL: 'dddd, D MMMM YYYY, A h:mm -നു',
        },
        calendar: {
            sameDay: '[ഇന്ന്] LT',
            nextDay: '[നാളെ] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[ഇന്നലെ] LT',
            lastWeek: '[കഴിഞ്ഞ] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s കഴിഞ്ഞ്',
            past: '%s മുൻപ്',
            s: 'അൽപ നിമിഷങ്ങൾ',
            ss: '%d സെക്കൻഡ്',
            m: 'ഒരു മിനിറ്റ്',
            mm: '%d മിനിറ്റ്',
            h: 'ഒരു മണിക്കൂർ',
            hh: '%d മണിക്കൂർ',
            d: 'ഒരു ദിവസം',
            dd: '%d ദിവസം',
            M: 'ഒരു മാസം',
            MM: '%d മാസം',
            y: 'ഒരു വർഷം',
            yy: '%d വർഷം',
        },
        meridiemParse: /രാത്രി|രാവിലെ|ഉച്ച കഴിഞ്ഞ്|വൈകുന്നേരം|രാത്രി/i,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (
                (meridiem === 'രാത്രി' && hour >= 4) ||
                meridiem === 'ഉച്ച കഴിഞ്ഞ്' ||
                meridiem === 'വൈകുന്നേരം'
            ) {
                return hour + 12;
            } else {
                return hour;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'രാത്രി';
            } else if (hour < 12) {
                return 'രാവിലെ';
            } else if (hour < 17) {
                return 'ഉച്ച കഴിഞ്ഞ്';
            } else if (hour < 20) {
                return 'വൈകുന്നേരം';
            } else {
                return 'രാത്രി';
            }
        },
    });

    //! moment.js locale configuration

    function translate$7(number, withoutSuffix, key, isFuture) {
        switch (key) {
            case 's':
                return withoutSuffix ? 'хэдхэн секунд' : 'хэдхэн секундын';
            case 'ss':
                return number + (withoutSuffix ? ' секунд' : ' секундын');
            case 'm':
            case 'mm':
                return number + (withoutSuffix ? ' минут' : ' минутын');
            case 'h':
            case 'hh':
                return number + (withoutSuffix ? ' цаг' : ' цагийн');
            case 'd':
            case 'dd':
                return number + (withoutSuffix ? ' өдөр' : ' өдрийн');
            case 'M':
            case 'MM':
                return number + (withoutSuffix ? ' сар' : ' сарын');
            case 'y':
            case 'yy':
                return number + (withoutSuffix ? ' жил' : ' жилийн');
            default:
                return number;
        }
    }

    hooks.defineLocale('mn', {
        months: 'Нэгдүгээр сар_Хоёрдугаар сар_Гуравдугаар сар_Дөрөвдүгээр сар_Тавдугаар сар_Зургадугаар сар_Долдугаар сар_Наймдугаар сар_Есдүгээр сар_Аравдугаар сар_Арван нэгдүгээр сар_Арван хоёрдугаар сар'.split(
            '_'
        ),
        monthsShort:
            '1 сар_2 сар_3 сар_4 сар_5 сар_6 сар_7 сар_8 сар_9 сар_10 сар_11 сар_12 сар'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'Ням_Даваа_Мягмар_Лхагва_Пүрэв_Баасан_Бямба'.split('_'),
        weekdaysShort: 'Ням_Дав_Мяг_Лха_Пүр_Баа_Бям'.split('_'),
        weekdaysMin: 'Ня_Да_Мя_Лх_Пү_Ба_Бя'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY-MM-DD',
            LL: 'YYYY оны MMMMын D',
            LLL: 'YYYY оны MMMMын D HH:mm',
            LLLL: 'dddd, YYYY оны MMMMын D HH:mm',
        },
        meridiemParse: /ҮӨ|ҮХ/i,
        isPM: function (input) {
            return input === 'ҮХ';
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ҮӨ';
            } else {
                return 'ҮХ';
            }
        },
        calendar: {
            sameDay: '[Өнөөдөр] LT',
            nextDay: '[Маргааш] LT',
            nextWeek: '[Ирэх] dddd LT',
            lastDay: '[Өчигдөр] LT',
            lastWeek: '[Өнгөрсөн] dddd LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s дараа',
            past: '%s өмнө',
            s: translate$7,
            ss: translate$7,
            m: translate$7,
            mm: translate$7,
            h: translate$7,
            hh: translate$7,
            d: translate$7,
            dd: translate$7,
            M: translate$7,
            MM: translate$7,
            y: translate$7,
            yy: translate$7,
        },
        dayOfMonthOrdinalParse: /\d{1,2} өдөр/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + ' өдөр';
                default:
                    return number;
            }
        },
    });

    //! moment.js locale configuration

    var symbolMap$c = {
            1: '१',
            2: '२',
            3: '३',
            4: '४',
            5: '५',
            6: '६',
            7: '७',
            8: '८',
            9: '९',
            0: '०',
        },
        numberMap$b = {
            '१': '1',
            '२': '2',
            '३': '3',
            '४': '4',
            '५': '5',
            '६': '6',
            '७': '7',
            '८': '8',
            '९': '9',
            '०': '0',
        };

    function relativeTimeMr(number, withoutSuffix, string, isFuture) {
        var output = '';
        if (withoutSuffix) {
            switch (string) {
                case 's':
                    output = 'काही सेकंद';
                    break;
                case 'ss':
                    output = '%d सेकंद';
                    break;
                case 'm':
                    output = 'एक मिनिट';
                    break;
                case 'mm':
                    output = '%d मिनिटे';
                    break;
                case 'h':
                    output = 'एक तास';
                    break;
                case 'hh':
                    output = '%d तास';
                    break;
                case 'd':
                    output = 'एक दिवस';
                    break;
                case 'dd':
                    output = '%d दिवस';
                    break;
                case 'M':
                    output = 'एक महिना';
                    break;
                case 'MM':
                    output = '%d महिने';
                    break;
                case 'y':
                    output = 'एक वर्ष';
                    break;
                case 'yy':
                    output = '%d वर्षे';
                    break;
            }
        } else {
            switch (string) {
                case 's':
                    output = 'काही सेकंदां';
                    break;
                case 'ss':
                    output = '%d सेकंदां';
                    break;
                case 'm':
                    output = 'एका मिनिटा';
                    break;
                case 'mm':
                    output = '%d मिनिटां';
                    break;
                case 'h':
                    output = 'एका तासा';
                    break;
                case 'hh':
                    output = '%d तासां';
                    break;
                case 'd':
                    output = 'एका दिवसा';
                    break;
                case 'dd':
                    output = '%d दिवसां';
                    break;
                case 'M':
                    output = 'एका महिन्या';
                    break;
                case 'MM':
                    output = '%d महिन्यां';
                    break;
                case 'y':
                    output = 'एका वर्षा';
                    break;
                case 'yy':
                    output = '%d वर्षां';
                    break;
            }
        }
        return output.replace(/%d/i, number);
    }

    hooks.defineLocale('mr', {
        months: 'जानेवारी_फेब्रुवारी_मार्च_एप्रिल_मे_जून_जुलै_ऑगस्ट_सप्टेंबर_ऑक्टोबर_नोव्हेंबर_डिसेंबर'.split(
            '_'
        ),
        monthsShort:
            'जाने._फेब्रु._मार्च._एप्रि._मे._जून._जुलै._ऑग._सप्टें._ऑक्टो._नोव्हें._डिसें.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'रविवार_सोमवार_मंगळवार_बुधवार_गुरूवार_शुक्रवार_शनिवार'.split('_'),
        weekdaysShort: 'रवि_सोम_मंगळ_बुध_गुरू_शुक्र_शनि'.split('_'),
        weekdaysMin: 'र_सो_मं_बु_गु_शु_श'.split('_'),
        longDateFormat: {
            LT: 'A h:mm वाजता',
            LTS: 'A h:mm:ss वाजता',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm वाजता',
            LLLL: 'dddd, D MMMM YYYY, A h:mm वाजता',
        },
        calendar: {
            sameDay: '[आज] LT',
            nextDay: '[उद्या] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[काल] LT',
            lastWeek: '[मागील] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%sमध्ये',
            past: '%sपूर्वी',
            s: relativeTimeMr,
            ss: relativeTimeMr,
            m: relativeTimeMr,
            mm: relativeTimeMr,
            h: relativeTimeMr,
            hh: relativeTimeMr,
            d: relativeTimeMr,
            dd: relativeTimeMr,
            M: relativeTimeMr,
            MM: relativeTimeMr,
            y: relativeTimeMr,
            yy: relativeTimeMr,
        },
        preparse: function (string) {
            return string.replace(/[१२३४५६७८९०]/g, function (match) {
                return numberMap$b[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$c[match];
            });
        },
        meridiemParse: /पहाटे|सकाळी|दुपारी|सायंकाळी|रात्री/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'पहाटे' || meridiem === 'सकाळी') {
                return hour;
            } else if (
                meridiem === 'दुपारी' ||
                meridiem === 'सायंकाळी' ||
                meridiem === 'रात्री'
            ) {
                return hour >= 12 ? hour : hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour >= 0 && hour < 6) {
                return 'पहाटे';
            } else if (hour < 12) {
                return 'सकाळी';
            } else if (hour < 17) {
                return 'दुपारी';
            } else if (hour < 20) {
                return 'सायंकाळी';
            } else {
                return 'रात्री';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ms-my', {
        months: 'Januari_Februari_Mac_April_Mei_Jun_Julai_Ogos_September_Oktober_November_Disember'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mac_Apr_Mei_Jun_Jul_Ogs_Sep_Okt_Nov_Dis'.split('_'),
        weekdays: 'Ahad_Isnin_Selasa_Rabu_Khamis_Jumaat_Sabtu'.split('_'),
        weekdaysShort: 'Ahd_Isn_Sel_Rab_Kha_Jum_Sab'.split('_'),
        weekdaysMin: 'Ah_Is_Sl_Rb_Km_Jm_Sb'.split('_'),
        longDateFormat: {
            LT: 'HH.mm',
            LTS: 'HH.mm.ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY [pukul] HH.mm',
            LLLL: 'dddd, D MMMM YYYY [pukul] HH.mm',
        },
        meridiemParse: /pagi|tengahari|petang|malam/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'pagi') {
                return hour;
            } else if (meridiem === 'tengahari') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'petang' || meridiem === 'malam') {
                return hour + 12;
            }
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 11) {
                return 'pagi';
            } else if (hours < 15) {
                return 'tengahari';
            } else if (hours < 19) {
                return 'petang';
            } else {
                return 'malam';
            }
        },
        calendar: {
            sameDay: '[Hari ini pukul] LT',
            nextDay: '[Esok pukul] LT',
            nextWeek: 'dddd [pukul] LT',
            lastDay: '[Kelmarin pukul] LT',
            lastWeek: 'dddd [lepas pukul] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'dalam %s',
            past: '%s yang lepas',
            s: 'beberapa saat',
            ss: '%d saat',
            m: 'seminit',
            mm: '%d minit',
            h: 'sejam',
            hh: '%d jam',
            d: 'sehari',
            dd: '%d hari',
            M: 'sebulan',
            MM: '%d bulan',
            y: 'setahun',
            yy: '%d tahun',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ms', {
        months: 'Januari_Februari_Mac_April_Mei_Jun_Julai_Ogos_September_Oktober_November_Disember'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mac_Apr_Mei_Jun_Jul_Ogs_Sep_Okt_Nov_Dis'.split('_'),
        weekdays: 'Ahad_Isnin_Selasa_Rabu_Khamis_Jumaat_Sabtu'.split('_'),
        weekdaysShort: 'Ahd_Isn_Sel_Rab_Kha_Jum_Sab'.split('_'),
        weekdaysMin: 'Ah_Is_Sl_Rb_Km_Jm_Sb'.split('_'),
        longDateFormat: {
            LT: 'HH.mm',
            LTS: 'HH.mm.ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY [pukul] HH.mm',
            LLLL: 'dddd, D MMMM YYYY [pukul] HH.mm',
        },
        meridiemParse: /pagi|tengahari|petang|malam/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'pagi') {
                return hour;
            } else if (meridiem === 'tengahari') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'petang' || meridiem === 'malam') {
                return hour + 12;
            }
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 11) {
                return 'pagi';
            } else if (hours < 15) {
                return 'tengahari';
            } else if (hours < 19) {
                return 'petang';
            } else {
                return 'malam';
            }
        },
        calendar: {
            sameDay: '[Hari ini pukul] LT',
            nextDay: '[Esok pukul] LT',
            nextWeek: 'dddd [pukul] LT',
            lastDay: '[Kelmarin pukul] LT',
            lastWeek: 'dddd [lepas pukul] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'dalam %s',
            past: '%s yang lepas',
            s: 'beberapa saat',
            ss: '%d saat',
            m: 'seminit',
            mm: '%d minit',
            h: 'sejam',
            hh: '%d jam',
            d: 'sehari',
            dd: '%d hari',
            M: 'sebulan',
            MM: '%d bulan',
            y: 'setahun',
            yy: '%d tahun',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('mt', {
        months: 'Jannar_Frar_Marzu_April_Mejju_Ġunju_Lulju_Awwissu_Settembru_Ottubru_Novembru_Diċembru'.split(
            '_'
        ),
        monthsShort: 'Jan_Fra_Mar_Apr_Mej_Ġun_Lul_Aww_Set_Ott_Nov_Diċ'.split('_'),
        weekdays:
            'Il-Ħadd_It-Tnejn_It-Tlieta_L-Erbgħa_Il-Ħamis_Il-Ġimgħa_Is-Sibt'.split(
                '_'
            ),
        weekdaysShort: 'Ħad_Tne_Tli_Erb_Ħam_Ġim_Sib'.split('_'),
        weekdaysMin: 'Ħa_Tn_Tl_Er_Ħa_Ġi_Si'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Illum fil-]LT',
            nextDay: '[Għada fil-]LT',
            nextWeek: 'dddd [fil-]LT',
            lastDay: '[Il-bieraħ fil-]LT',
            lastWeek: 'dddd [li għadda] [fil-]LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'f’ %s',
            past: '%s ilu',
            s: 'ftit sekondi',
            ss: '%d sekondi',
            m: 'minuta',
            mm: '%d minuti',
            h: 'siegħa',
            hh: '%d siegħat',
            d: 'ġurnata',
            dd: '%d ġranet',
            M: 'xahar',
            MM: '%d xhur',
            y: 'sena',
            yy: '%d sni',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$d = {
            1: '၁',
            2: '၂',
            3: '၃',
            4: '၄',
            5: '၅',
            6: '၆',
            7: '၇',
            8: '၈',
            9: '၉',
            0: '၀',
        },
        numberMap$c = {
            '၁': '1',
            '၂': '2',
            '၃': '3',
            '၄': '4',
            '၅': '5',
            '၆': '6',
            '၇': '7',
            '၈': '8',
            '၉': '9',
            '၀': '0',
        };

    hooks.defineLocale('my', {
        months: 'ဇန်နဝါရီ_ဖေဖော်ဝါရီ_မတ်_ဧပြီ_မေ_ဇွန်_ဇူလိုင်_သြဂုတ်_စက်တင်ဘာ_အောက်တိုဘာ_နိုဝင်ဘာ_ဒီဇင်ဘာ'.split(
            '_'
        ),
        monthsShort: 'ဇန်_ဖေ_မတ်_ပြီ_မေ_ဇွန်_လိုင်_သြ_စက်_အောက်_နို_ဒီ'.split('_'),
        weekdays: 'တနင်္ဂနွေ_တနင်္လာ_အင်္ဂါ_ဗုဒ္ဓဟူး_ကြာသပတေး_သောကြာ_စနေ'.split(
            '_'
        ),
        weekdaysShort: 'နွေ_လာ_ဂါ_ဟူး_ကြာ_သော_နေ'.split('_'),
        weekdaysMin: 'နွေ_လာ_ဂါ_ဟူး_ကြာ_သော_နေ'.split('_'),

        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[ယနေ.] LT [မှာ]',
            nextDay: '[မနက်ဖြန်] LT [မှာ]',
            nextWeek: 'dddd LT [မှာ]',
            lastDay: '[မနေ.က] LT [မှာ]',
            lastWeek: '[ပြီးခဲ့သော] dddd LT [မှာ]',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'လာမည့် %s မှာ',
            past: 'လွန်ခဲ့သော %s က',
            s: 'စက္ကန်.အနည်းငယ်',
            ss: '%d စက္ကန့်',
            m: 'တစ်မိနစ်',
            mm: '%d မိနစ်',
            h: 'တစ်နာရီ',
            hh: '%d နာရီ',
            d: 'တစ်ရက်',
            dd: '%d ရက်',
            M: 'တစ်လ',
            MM: '%d လ',
            y: 'တစ်နှစ်',
            yy: '%d နှစ်',
        },
        preparse: function (string) {
            return string.replace(/[၁၂၃၄၅၆၇၈၉၀]/g, function (match) {
                return numberMap$c[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$d[match];
            });
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('nb', {
        months: 'januar_februar_mars_april_mai_juni_juli_august_september_oktober_november_desember'.split(
            '_'
        ),
        monthsShort:
            'jan._feb._mars_apr._mai_juni_juli_aug._sep._okt._nov._des.'.split('_'),
        monthsParseExact: true,
        weekdays: 'søndag_mandag_tirsdag_onsdag_torsdag_fredag_lørdag'.split('_'),
        weekdaysShort: 'sø._ma._ti._on._to._fr._lø.'.split('_'),
        weekdaysMin: 'sø_ma_ti_on_to_fr_lø'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY [kl.] HH:mm',
            LLLL: 'dddd D. MMMM YYYY [kl.] HH:mm',
        },
        calendar: {
            sameDay: '[i dag kl.] LT',
            nextDay: '[i morgen kl.] LT',
            nextWeek: 'dddd [kl.] LT',
            lastDay: '[i går kl.] LT',
            lastWeek: '[forrige] dddd [kl.] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'om %s',
            past: '%s siden',
            s: 'noen sekunder',
            ss: '%d sekunder',
            m: 'ett minutt',
            mm: '%d minutter',
            h: 'en time',
            hh: '%d timer',
            d: 'en dag',
            dd: '%d dager',
            w: 'en uke',
            ww: '%d uker',
            M: 'en måned',
            MM: '%d måneder',
            y: 'ett år',
            yy: '%d år',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$e = {
            1: '१',
            2: '२',
            3: '३',
            4: '४',
            5: '५',
            6: '६',
            7: '७',
            8: '८',
            9: '९',
            0: '०',
        },
        numberMap$d = {
            '१': '1',
            '२': '2',
            '३': '3',
            '४': '4',
            '५': '5',
            '६': '6',
            '७': '7',
            '८': '8',
            '९': '9',
            '०': '0',
        };

    hooks.defineLocale('ne', {
        months: 'जनवरी_फेब्रुवरी_मार्च_अप्रिल_मई_जुन_जुलाई_अगष्ट_सेप्टेम्बर_अक्टोबर_नोभेम्बर_डिसेम्बर'.split(
            '_'
        ),
        monthsShort:
            'जन._फेब्रु._मार्च_अप्रि._मई_जुन_जुलाई._अग._सेप्ट._अक्टो._नोभे._डिसे.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'आइतबार_सोमबार_मङ्गलबार_बुधबार_बिहिबार_शुक्रबार_शनिबार'.split(
            '_'
        ),
        weekdaysShort: 'आइत._सोम._मङ्गल._बुध._बिहि._शुक्र._शनि.'.split('_'),
        weekdaysMin: 'आ._सो._मं._बु._बि._शु._श.'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'Aको h:mm बजे',
            LTS: 'Aको h:mm:ss बजे',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, Aको h:mm बजे',
            LLLL: 'dddd, D MMMM YYYY, Aको h:mm बजे',
        },
        preparse: function (string) {
            return string.replace(/[१२३४५६७८९०]/g, function (match) {
                return numberMap$d[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$e[match];
            });
        },
        meridiemParse: /राति|बिहान|दिउँसो|साँझ/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'राति') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'बिहान') {
                return hour;
            } else if (meridiem === 'दिउँसो') {
                return hour >= 10 ? hour : hour + 12;
            } else if (meridiem === 'साँझ') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 3) {
                return 'राति';
            } else if (hour < 12) {
                return 'बिहान';
            } else if (hour < 16) {
                return 'दिउँसो';
            } else if (hour < 20) {
                return 'साँझ';
            } else {
                return 'राति';
            }
        },
        calendar: {
            sameDay: '[आज] LT',
            nextDay: '[भोलि] LT',
            nextWeek: '[आउँदो] dddd[,] LT',
            lastDay: '[हिजो] LT',
            lastWeek: '[गएको] dddd[,] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%sमा',
            past: '%s अगाडि',
            s: 'केही क्षण',
            ss: '%d सेकेण्ड',
            m: 'एक मिनेट',
            mm: '%d मिनेट',
            h: 'एक घण्टा',
            hh: '%d घण्टा',
            d: 'एक दिन',
            dd: '%d दिन',
            M: 'एक महिना',
            MM: '%d महिना',
            y: 'एक बर्ष',
            yy: '%d बर्ष',
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsShortWithDots$1 =
            'jan._feb._mrt._apr._mei_jun._jul._aug._sep._okt._nov._dec.'.split('_'),
        monthsShortWithoutDots$1 =
            'jan_feb_mrt_apr_mei_jun_jul_aug_sep_okt_nov_dec'.split('_'),
        monthsParse$8 = [
            /^jan/i,
            /^feb/i,
            /^maart|mrt.?$/i,
            /^apr/i,
            /^mei$/i,
            /^jun[i.]?$/i,
            /^jul[i.]?$/i,
            /^aug/i,
            /^sep/i,
            /^okt/i,
            /^nov/i,
            /^dec/i,
        ],
        monthsRegex$8 =
            /^(januari|februari|maart|april|mei|ju[nl]i|augustus|september|oktober|november|december|jan\.?|feb\.?|mrt\.?|apr\.?|ju[nl]\.?|aug\.?|sep\.?|okt\.?|nov\.?|dec\.?)/i;

    hooks.defineLocale('nl-be', {
        months: 'januari_februari_maart_april_mei_juni_juli_augustus_september_oktober_november_december'.split(
            '_'
        ),
        monthsShort: function (m, format) {
            if (!m) {
                return monthsShortWithDots$1;
            } else if (/-MMM-/.test(format)) {
                return monthsShortWithoutDots$1[m.month()];
            } else {
                return monthsShortWithDots$1[m.month()];
            }
        },

        monthsRegex: monthsRegex$8,
        monthsShortRegex: monthsRegex$8,
        monthsStrictRegex:
            /^(januari|februari|maart|april|mei|ju[nl]i|augustus|september|oktober|november|december)/i,
        monthsShortStrictRegex:
            /^(jan\.?|feb\.?|mrt\.?|apr\.?|mei|ju[nl]\.?|aug\.?|sep\.?|okt\.?|nov\.?|dec\.?)/i,

        monthsParse: monthsParse$8,
        longMonthsParse: monthsParse$8,
        shortMonthsParse: monthsParse$8,

        weekdays:
            'zondag_maandag_dinsdag_woensdag_donderdag_vrijdag_zaterdag'.split('_'),
        weekdaysShort: 'zo._ma._di._wo._do._vr._za.'.split('_'),
        weekdaysMin: 'zo_ma_di_wo_do_vr_za'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[vandaag om] LT',
            nextDay: '[morgen om] LT',
            nextWeek: 'dddd [om] LT',
            lastDay: '[gisteren om] LT',
            lastWeek: '[afgelopen] dddd [om] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'over %s',
            past: '%s geleden',
            s: 'een paar seconden',
            ss: '%d seconden',
            m: 'één minuut',
            mm: '%d minuten',
            h: 'één uur',
            hh: '%d uur',
            d: 'één dag',
            dd: '%d dagen',
            M: 'één maand',
            MM: '%d maanden',
            y: 'één jaar',
            yy: '%d jaar',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(ste|de)/,
        ordinal: function (number) {
            return (
                number +
                (number === 1 || number === 8 || number >= 20 ? 'ste' : 'de')
            );
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsShortWithDots$2 =
            'jan._feb._mrt._apr._mei_jun._jul._aug._sep._okt._nov._dec.'.split('_'),
        monthsShortWithoutDots$2 =
            'jan_feb_mrt_apr_mei_jun_jul_aug_sep_okt_nov_dec'.split('_'),
        monthsParse$9 = [
            /^jan/i,
            /^feb/i,
            /^maart|mrt.?$/i,
            /^apr/i,
            /^mei$/i,
            /^jun[i.]?$/i,
            /^jul[i.]?$/i,
            /^aug/i,
            /^sep/i,
            /^okt/i,
            /^nov/i,
            /^dec/i,
        ],
        monthsRegex$9 =
            /^(januari|februari|maart|april|mei|ju[nl]i|augustus|september|oktober|november|december|jan\.?|feb\.?|mrt\.?|apr\.?|ju[nl]\.?|aug\.?|sep\.?|okt\.?|nov\.?|dec\.?)/i;

    hooks.defineLocale('nl', {
        months: 'januari_februari_maart_april_mei_juni_juli_augustus_september_oktober_november_december'.split(
            '_'
        ),
        monthsShort: function (m, format) {
            if (!m) {
                return monthsShortWithDots$2;
            } else if (/-MMM-/.test(format)) {
                return monthsShortWithoutDots$2[m.month()];
            } else {
                return monthsShortWithDots$2[m.month()];
            }
        },

        monthsRegex: monthsRegex$9,
        monthsShortRegex: monthsRegex$9,
        monthsStrictRegex:
            /^(januari|februari|maart|april|mei|ju[nl]i|augustus|september|oktober|november|december)/i,
        monthsShortStrictRegex:
            /^(jan\.?|feb\.?|mrt\.?|apr\.?|mei|ju[nl]\.?|aug\.?|sep\.?|okt\.?|nov\.?|dec\.?)/i,

        monthsParse: monthsParse$9,
        longMonthsParse: monthsParse$9,
        shortMonthsParse: monthsParse$9,

        weekdays:
            'zondag_maandag_dinsdag_woensdag_donderdag_vrijdag_zaterdag'.split('_'),
        weekdaysShort: 'zo._ma._di._wo._do._vr._za.'.split('_'),
        weekdaysMin: 'zo_ma_di_wo_do_vr_za'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD-MM-YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[vandaag om] LT',
            nextDay: '[morgen om] LT',
            nextWeek: 'dddd [om] LT',
            lastDay: '[gisteren om] LT',
            lastWeek: '[afgelopen] dddd [om] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'over %s',
            past: '%s geleden',
            s: 'een paar seconden',
            ss: '%d seconden',
            m: 'één minuut',
            mm: '%d minuten',
            h: 'één uur',
            hh: '%d uur',
            d: 'één dag',
            dd: '%d dagen',
            w: 'één week',
            ww: '%d weken',
            M: 'één maand',
            MM: '%d maanden',
            y: 'één jaar',
            yy: '%d jaar',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(ste|de)/,
        ordinal: function (number) {
            return (
                number +
                (number === 1 || number === 8 || number >= 20 ? 'ste' : 'de')
            );
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('nn', {
        months: 'januar_februar_mars_april_mai_juni_juli_august_september_oktober_november_desember'.split(
            '_'
        ),
        monthsShort:
            'jan._feb._mars_apr._mai_juni_juli_aug._sep._okt._nov._des.'.split('_'),
        monthsParseExact: true,
        weekdays: 'sundag_måndag_tysdag_onsdag_torsdag_fredag_laurdag'.split('_'),
        weekdaysShort: 'su._må._ty._on._to._fr._lau.'.split('_'),
        weekdaysMin: 'su_må_ty_on_to_fr_la'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY [kl.] H:mm',
            LLLL: 'dddd D. MMMM YYYY [kl.] HH:mm',
        },
        calendar: {
            sameDay: '[I dag klokka] LT',
            nextDay: '[I morgon klokka] LT',
            nextWeek: 'dddd [klokka] LT',
            lastDay: '[I går klokka] LT',
            lastWeek: '[Føregåande] dddd [klokka] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'om %s',
            past: '%s sidan',
            s: 'nokre sekund',
            ss: '%d sekund',
            m: 'eit minutt',
            mm: '%d minutt',
            h: 'ein time',
            hh: '%d timar',
            d: 'ein dag',
            dd: '%d dagar',
            w: 'ei veke',
            ww: '%d veker',
            M: 'ein månad',
            MM: '%d månader',
            y: 'eit år',
            yy: '%d år',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('oc-lnc', {
        months: {
            standalone:
                'genièr_febrièr_març_abril_mai_junh_julhet_agost_setembre_octòbre_novembre_decembre'.split(
                    '_'
                ),
            format: "de genièr_de febrièr_de març_d'abril_de mai_de junh_de julhet_d'agost_de setembre_d'octòbre_de novembre_de decembre".split(
                '_'
            ),
            isFormat: /D[oD]?(\s)+MMMM/,
        },
        monthsShort:
            'gen._febr._març_abr._mai_junh_julh._ago._set._oct._nov._dec.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'dimenge_diluns_dimars_dimècres_dijòus_divendres_dissabte'.split(
            '_'
        ),
        weekdaysShort: 'dg._dl._dm._dc._dj._dv._ds.'.split('_'),
        weekdaysMin: 'dg_dl_dm_dc_dj_dv_ds'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM [de] YYYY',
            ll: 'D MMM YYYY',
            LLL: 'D MMMM [de] YYYY [a] H:mm',
            lll: 'D MMM YYYY, H:mm',
            LLLL: 'dddd D MMMM [de] YYYY [a] H:mm',
            llll: 'ddd D MMM YYYY, H:mm',
        },
        calendar: {
            sameDay: '[uèi a] LT',
            nextDay: '[deman a] LT',
            nextWeek: 'dddd [a] LT',
            lastDay: '[ièr a] LT',
            lastWeek: 'dddd [passat a] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: "d'aquí %s",
            past: 'fa %s',
            s: 'unas segondas',
            ss: '%d segondas',
            m: 'una minuta',
            mm: '%d minutas',
            h: 'una ora',
            hh: '%d oras',
            d: 'un jorn',
            dd: '%d jorns',
            M: 'un mes',
            MM: '%d meses',
            y: 'un an',
            yy: '%d ans',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(r|n|t|è|a)/,
        ordinal: function (number, period) {
            var output =
                number === 1
                    ? 'r'
                    : number === 2
                    ? 'n'
                    : number === 3
                    ? 'r'
                    : number === 4
                    ? 't'
                    : 'è';
            if (period === 'w' || period === 'W') {
                output = 'a';
            }
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4,
        },
    });

    //! moment.js locale configuration

    var symbolMap$f = {
            1: '੧',
            2: '੨',
            3: '੩',
            4: '੪',
            5: '੫',
            6: '੬',
            7: '੭',
            8: '੮',
            9: '੯',
            0: '੦',
        },
        numberMap$e = {
            '੧': '1',
            '੨': '2',
            '੩': '3',
            '੪': '4',
            '੫': '5',
            '੬': '6',
            '੭': '7',
            '੮': '8',
            '੯': '9',
            '੦': '0',
        };

    hooks.defineLocale('pa-in', {
        // There are months name as per Nanakshahi Calendar but they are not used as rigidly in modern Punjabi.
        months: 'ਜਨਵਰੀ_ਫ਼ਰਵਰੀ_ਮਾਰਚ_ਅਪ੍ਰੈਲ_ਮਈ_ਜੂਨ_ਜੁਲਾਈ_ਅਗਸਤ_ਸਤੰਬਰ_ਅਕਤੂਬਰ_ਨਵੰਬਰ_ਦਸੰਬਰ'.split(
            '_'
        ),
        monthsShort:
            'ਜਨਵਰੀ_ਫ਼ਰਵਰੀ_ਮਾਰਚ_ਅਪ੍ਰੈਲ_ਮਈ_ਜੂਨ_ਜੁਲਾਈ_ਅਗਸਤ_ਸਤੰਬਰ_ਅਕਤੂਬਰ_ਨਵੰਬਰ_ਦਸੰਬਰ'.split(
                '_'
            ),
        weekdays: 'ਐਤਵਾਰ_ਸੋਮਵਾਰ_ਮੰਗਲਵਾਰ_ਬੁਧਵਾਰ_ਵੀਰਵਾਰ_ਸ਼ੁੱਕਰਵਾਰ_ਸ਼ਨੀਚਰਵਾਰ'.split(
            '_'
        ),
        weekdaysShort: 'ਐਤ_ਸੋਮ_ਮੰਗਲ_ਬੁਧ_ਵੀਰ_ਸ਼ੁਕਰ_ਸ਼ਨੀ'.split('_'),
        weekdaysMin: 'ਐਤ_ਸੋਮ_ਮੰਗਲ_ਬੁਧ_ਵੀਰ_ਸ਼ੁਕਰ_ਸ਼ਨੀ'.split('_'),
        longDateFormat: {
            LT: 'A h:mm ਵਜੇ',
            LTS: 'A h:mm:ss ਵਜੇ',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm ਵਜੇ',
            LLLL: 'dddd, D MMMM YYYY, A h:mm ਵਜੇ',
        },
        calendar: {
            sameDay: '[ਅਜ] LT',
            nextDay: '[ਕਲ] LT',
            nextWeek: '[ਅਗਲਾ] dddd, LT',
            lastDay: '[ਕਲ] LT',
            lastWeek: '[ਪਿਛਲੇ] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s ਵਿੱਚ',
            past: '%s ਪਿਛਲੇ',
            s: 'ਕੁਝ ਸਕਿੰਟ',
            ss: '%d ਸਕਿੰਟ',
            m: 'ਇਕ ਮਿੰਟ',
            mm: '%d ਮਿੰਟ',
            h: 'ਇੱਕ ਘੰਟਾ',
            hh: '%d ਘੰਟੇ',
            d: 'ਇੱਕ ਦਿਨ',
            dd: '%d ਦਿਨ',
            M: 'ਇੱਕ ਮਹੀਨਾ',
            MM: '%d ਮਹੀਨੇ',
            y: 'ਇੱਕ ਸਾਲ',
            yy: '%d ਸਾਲ',
        },
        preparse: function (string) {
            return string.replace(/[੧੨੩੪੫੬੭੮੯੦]/g, function (match) {
                return numberMap$e[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$f[match];
            });
        },
        // Punjabi notation for meridiems are quite fuzzy in practice. While there exists
        // a rigid notion of a 'Pahar' it is not used as rigidly in modern Punjabi.
        meridiemParse: /ਰਾਤ|ਸਵੇਰ|ਦੁਪਹਿਰ|ਸ਼ਾਮ/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'ਰਾਤ') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'ਸਵੇਰ') {
                return hour;
            } else if (meridiem === 'ਦੁਪਹਿਰ') {
                return hour >= 10 ? hour : hour + 12;
            } else if (meridiem === 'ਸ਼ਾਮ') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'ਰਾਤ';
            } else if (hour < 10) {
                return 'ਸਵੇਰ';
            } else if (hour < 17) {
                return 'ਦੁਪਹਿਰ';
            } else if (hour < 20) {
                return 'ਸ਼ਾਮ';
            } else {
                return 'ਰਾਤ';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var monthsNominative =
            'styczeń_luty_marzec_kwiecień_maj_czerwiec_lipiec_sierpień_wrzesień_październik_listopad_grudzień'.split(
                '_'
            ),
        monthsSubjective =
            'stycznia_lutego_marca_kwietnia_maja_czerwca_lipca_sierpnia_września_października_listopada_grudnia'.split(
                '_'
            ),
        monthsParse$a = [
            /^sty/i,
            /^lut/i,
            /^mar/i,
            /^kwi/i,
            /^maj/i,
            /^cze/i,
            /^lip/i,
            /^sie/i,
            /^wrz/i,
            /^paź/i,
            /^lis/i,
            /^gru/i,
        ];
    function plural$3(n) {
        return n % 10 < 5 && n % 10 > 1 && ~~(n / 10) % 10 !== 1;
    }
    function translate$8(number, withoutSuffix, key) {
        var result = number + ' ';
        switch (key) {
            case 'ss':
                return result + (plural$3(number) ? 'sekundy' : 'sekund');
            case 'm':
                return withoutSuffix ? 'minuta' : 'minutę';
            case 'mm':
                return result + (plural$3(number) ? 'minuty' : 'minut');
            case 'h':
                return withoutSuffix ? 'godzina' : 'godzinę';
            case 'hh':
                return result + (plural$3(number) ? 'godziny' : 'godzin');
            case 'ww':
                return result + (plural$3(number) ? 'tygodnie' : 'tygodni');
            case 'MM':
                return result + (plural$3(number) ? 'miesiące' : 'miesięcy');
            case 'yy':
                return result + (plural$3(number) ? 'lata' : 'lat');
        }
    }

    hooks.defineLocale('pl', {
        months: function (momentToFormat, format) {
            if (!momentToFormat) {
                return monthsNominative;
            } else if (/D MMMM/.test(format)) {
                return monthsSubjective[momentToFormat.month()];
            } else {
                return monthsNominative[momentToFormat.month()];
            }
        },
        monthsShort: 'sty_lut_mar_kwi_maj_cze_lip_sie_wrz_paź_lis_gru'.split('_'),
        monthsParse: monthsParse$a,
        longMonthsParse: monthsParse$a,
        shortMonthsParse: monthsParse$a,
        weekdays:
            'niedziela_poniedziałek_wtorek_środa_czwartek_piątek_sobota'.split('_'),
        weekdaysShort: 'ndz_pon_wt_śr_czw_pt_sob'.split('_'),
        weekdaysMin: 'Nd_Pn_Wt_Śr_Cz_Pt_So'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Dziś o] LT',
            nextDay: '[Jutro o] LT',
            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[W niedzielę o] LT';

                    case 2:
                        return '[We wtorek o] LT';

                    case 3:
                        return '[W środę o] LT';

                    case 6:
                        return '[W sobotę o] LT';

                    default:
                        return '[W] dddd [o] LT';
                }
            },
            lastDay: '[Wczoraj o] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[W zeszłą niedzielę o] LT';
                    case 3:
                        return '[W zeszłą środę o] LT';
                    case 6:
                        return '[W zeszłą sobotę o] LT';
                    default:
                        return '[W zeszły] dddd [o] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'za %s',
            past: '%s temu',
            s: 'kilka sekund',
            ss: translate$8,
            m: translate$8,
            mm: translate$8,
            h: translate$8,
            hh: translate$8,
            d: '1 dzień',
            dd: '%d dni',
            w: 'tydzień',
            ww: translate$8,
            M: 'miesiąc',
            MM: translate$8,
            y: 'rok',
            yy: translate$8,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('pt-br', {
        months: 'janeiro_fevereiro_março_abril_maio_junho_julho_agosto_setembro_outubro_novembro_dezembro'.split(
            '_'
        ),
        monthsShort: 'jan_fev_mar_abr_mai_jun_jul_ago_set_out_nov_dez'.split('_'),
        weekdays:
            'domingo_segunda-feira_terça-feira_quarta-feira_quinta-feira_sexta-feira_sábado'.split(
                '_'
            ),
        weekdaysShort: 'dom_seg_ter_qua_qui_sex_sáb'.split('_'),
        weekdaysMin: 'do_2ª_3ª_4ª_5ª_6ª_sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D [de] MMMM [de] YYYY',
            LLL: 'D [de] MMMM [de] YYYY [às] HH:mm',
            LLLL: 'dddd, D [de] MMMM [de] YYYY [às] HH:mm',
        },
        calendar: {
            sameDay: '[Hoje às] LT',
            nextDay: '[Amanhã às] LT',
            nextWeek: 'dddd [às] LT',
            lastDay: '[Ontem às] LT',
            lastWeek: function () {
                return this.day() === 0 || this.day() === 6
                    ? '[Último] dddd [às] LT' // Saturday + Sunday
                    : '[Última] dddd [às] LT'; // Monday - Friday
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'em %s',
            past: 'há %s',
            s: 'poucos segundos',
            ss: '%d segundos',
            m: 'um minuto',
            mm: '%d minutos',
            h: 'uma hora',
            hh: '%d horas',
            d: 'um dia',
            dd: '%d dias',
            M: 'um mês',
            MM: '%d meses',
            y: 'um ano',
            yy: '%d anos',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        invalidDate: 'Data inválida',
    });

    //! moment.js locale configuration

    hooks.defineLocale('pt', {
        months: 'janeiro_fevereiro_março_abril_maio_junho_julho_agosto_setembro_outubro_novembro_dezembro'.split(
            '_'
        ),
        monthsShort: 'jan_fev_mar_abr_mai_jun_jul_ago_set_out_nov_dez'.split('_'),
        weekdays:
            'Domingo_Segunda-feira_Terça-feira_Quarta-feira_Quinta-feira_Sexta-feira_Sábado'.split(
                '_'
            ),
        weekdaysShort: 'Dom_Seg_Ter_Qua_Qui_Sex_Sáb'.split('_'),
        weekdaysMin: 'Do_2ª_3ª_4ª_5ª_6ª_Sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D [de] MMMM [de] YYYY',
            LLL: 'D [de] MMMM [de] YYYY HH:mm',
            LLLL: 'dddd, D [de] MMMM [de] YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Hoje às] LT',
            nextDay: '[Amanhã às] LT',
            nextWeek: 'dddd [às] LT',
            lastDay: '[Ontem às] LT',
            lastWeek: function () {
                return this.day() === 0 || this.day() === 6
                    ? '[Último] dddd [às] LT' // Saturday + Sunday
                    : '[Última] dddd [às] LT'; // Monday - Friday
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'em %s',
            past: 'há %s',
            s: 'segundos',
            ss: '%d segundos',
            m: 'um minuto',
            mm: '%d minutos',
            h: 'uma hora',
            hh: '%d horas',
            d: 'um dia',
            dd: '%d dias',
            w: 'uma semana',
            ww: '%d semanas',
            M: 'um mês',
            MM: '%d meses',
            y: 'um ano',
            yy: '%d anos',
        },
        dayOfMonthOrdinalParse: /\d{1,2}º/,
        ordinal: '%dº',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function relativeTimeWithPlural$2(number, withoutSuffix, key) {
        var format = {
                ss: 'secunde',
                mm: 'minute',
                hh: 'ore',
                dd: 'zile',
                ww: 'săptămâni',
                MM: 'luni',
                yy: 'ani',
            },
            separator = ' ';
        if (number % 100 >= 20 || (number >= 100 && number % 100 === 0)) {
            separator = ' de ';
        }
        return number + separator + format[key];
    }

    hooks.defineLocale('ro', {
        months: 'ianuarie_februarie_martie_aprilie_mai_iunie_iulie_august_septembrie_octombrie_noiembrie_decembrie'.split(
            '_'
        ),
        monthsShort:
            'ian._feb._mart._apr._mai_iun._iul._aug._sept._oct._nov._dec.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'duminică_luni_marți_miercuri_joi_vineri_sâmbătă'.split('_'),
        weekdaysShort: 'Dum_Lun_Mar_Mie_Joi_Vin_Sâm'.split('_'),
        weekdaysMin: 'Du_Lu_Ma_Mi_Jo_Vi_Sâ'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY H:mm',
            LLLL: 'dddd, D MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[azi la] LT',
            nextDay: '[mâine la] LT',
            nextWeek: 'dddd [la] LT',
            lastDay: '[ieri la] LT',
            lastWeek: '[fosta] dddd [la] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'peste %s',
            past: '%s în urmă',
            s: 'câteva secunde',
            ss: relativeTimeWithPlural$2,
            m: 'un minut',
            mm: relativeTimeWithPlural$2,
            h: 'o oră',
            hh: relativeTimeWithPlural$2,
            d: 'o zi',
            dd: relativeTimeWithPlural$2,
            w: 'o săptămână',
            ww: relativeTimeWithPlural$2,
            M: 'o lună',
            MM: relativeTimeWithPlural$2,
            y: 'un an',
            yy: relativeTimeWithPlural$2,
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function plural$4(word, num) {
        var forms = word.split('_');
        return num % 10 === 1 && num % 100 !== 11
            ? forms[0]
            : num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20)
            ? forms[1]
            : forms[2];
    }
    function relativeTimeWithPlural$3(number, withoutSuffix, key) {
        var format = {
            ss: withoutSuffix ? 'секунда_секунды_секунд' : 'секунду_секунды_секунд',
            mm: withoutSuffix ? 'минута_минуты_минут' : 'минуту_минуты_минут',
            hh: 'час_часа_часов',
            dd: 'день_дня_дней',
            ww: 'неделя_недели_недель',
            MM: 'месяц_месяца_месяцев',
            yy: 'год_года_лет',
        };
        if (key === 'm') {
            return withoutSuffix ? 'минута' : 'минуту';
        } else {
            return number + ' ' + plural$4(format[key], +number);
        }
    }
    var monthsParse$b = [
        /^янв/i,
        /^фев/i,
        /^мар/i,
        /^апр/i,
        /^ма[йя]/i,
        /^июн/i,
        /^июл/i,
        /^авг/i,
        /^сен/i,
        /^окт/i,
        /^ноя/i,
        /^дек/i,
    ];

    // http://new.gramota.ru/spravka/rules/139-prop : § 103
    // Сокращения месяцев: http://new.gramota.ru/spravka/buro/search-answer?s=242637
    // CLDR data:          http://www.unicode.org/cldr/charts/28/summary/ru.html#1753
    hooks.defineLocale('ru', {
        months: {
            format: 'января_февраля_марта_апреля_мая_июня_июля_августа_сентября_октября_ноября_декабря'.split(
                '_'
            ),
            standalone:
                'январь_февраль_март_апрель_май_июнь_июль_август_сентябрь_октябрь_ноябрь_декабрь'.split(
                    '_'
                ),
        },
        monthsShort: {
            // по CLDR именно "июл." и "июн.", но какой смысл менять букву на точку?
            format: 'янв._февр._мар._апр._мая_июня_июля_авг._сент._окт._нояб._дек.'.split(
                '_'
            ),
            standalone:
                'янв._февр._март_апр._май_июнь_июль_авг._сент._окт._нояб._дек.'.split(
                    '_'
                ),
        },
        weekdays: {
            standalone:
                'воскресенье_понедельник_вторник_среда_четверг_пятница_суббота'.split(
                    '_'
                ),
            format: 'воскресенье_понедельник_вторник_среду_четверг_пятницу_субботу'.split(
                '_'
            ),
            isFormat: /\[ ?[Вв] ?(?:прошлую|следующую|эту)? ?] ?dddd/,
        },
        weekdaysShort: 'вс_пн_вт_ср_чт_пт_сб'.split('_'),
        weekdaysMin: 'вс_пн_вт_ср_чт_пт_сб'.split('_'),
        monthsParse: monthsParse$b,
        longMonthsParse: monthsParse$b,
        shortMonthsParse: monthsParse$b,

        // полные названия с падежами, по три буквы, для некоторых, по 4 буквы, сокращения с точкой и без точки
        monthsRegex:
            /^(январ[ья]|янв\.?|феврал[ья]|февр?\.?|марта?|мар\.?|апрел[ья]|апр\.?|ма[йя]|июн[ья]|июн\.?|июл[ья]|июл\.?|августа?|авг\.?|сентябр[ья]|сент?\.?|октябр[ья]|окт\.?|ноябр[ья]|нояб?\.?|декабр[ья]|дек\.?)/i,

        // копия предыдущего
        monthsShortRegex:
            /^(январ[ья]|янв\.?|феврал[ья]|февр?\.?|марта?|мар\.?|апрел[ья]|апр\.?|ма[йя]|июн[ья]|июн\.?|июл[ья]|июл\.?|августа?|авг\.?|сентябр[ья]|сент?\.?|октябр[ья]|окт\.?|ноябр[ья]|нояб?\.?|декабр[ья]|дек\.?)/i,

        // полные названия с падежами
        monthsStrictRegex:
            /^(январ[яь]|феврал[яь]|марта?|апрел[яь]|ма[яй]|июн[яь]|июл[яь]|августа?|сентябр[яь]|октябр[яь]|ноябр[яь]|декабр[яь])/i,

        // Выражение, которое соответствует только сокращённым формам
        monthsShortStrictRegex:
            /^(янв\.|февр?\.|мар[т.]|апр\.|ма[яй]|июн[ья.]|июл[ья.]|авг\.|сент?\.|окт\.|нояб?\.|дек\.)/i,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY г.',
            LLL: 'D MMMM YYYY г., H:mm',
            LLLL: 'dddd, D MMMM YYYY г., H:mm',
        },
        calendar: {
            sameDay: '[Сегодня, в] LT',
            nextDay: '[Завтра, в] LT',
            lastDay: '[Вчера, в] LT',
            nextWeek: function (now) {
                if (now.week() !== this.week()) {
                    switch (this.day()) {
                        case 0:
                            return '[В следующее] dddd, [в] LT';
                        case 1:
                        case 2:
                        case 4:
                            return '[В следующий] dddd, [в] LT';
                        case 3:
                        case 5:
                        case 6:
                            return '[В следующую] dddd, [в] LT';
                    }
                } else {
                    if (this.day() === 2) {
                        return '[Во] dddd, [в] LT';
                    } else {
                        return '[В] dddd, [в] LT';
                    }
                }
            },
            lastWeek: function (now) {
                if (now.week() !== this.week()) {
                    switch (this.day()) {
                        case 0:
                            return '[В прошлое] dddd, [в] LT';
                        case 1:
                        case 2:
                        case 4:
                            return '[В прошлый] dddd, [в] LT';
                        case 3:
                        case 5:
                        case 6:
                            return '[В прошлую] dddd, [в] LT';
                    }
                } else {
                    if (this.day() === 2) {
                        return '[Во] dddd, [в] LT';
                    } else {
                        return '[В] dddd, [в] LT';
                    }
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'через %s',
            past: '%s назад',
            s: 'несколько секунд',
            ss: relativeTimeWithPlural$3,
            m: relativeTimeWithPlural$3,
            mm: relativeTimeWithPlural$3,
            h: 'час',
            hh: relativeTimeWithPlural$3,
            d: 'день',
            dd: relativeTimeWithPlural$3,
            w: 'неделя',
            ww: relativeTimeWithPlural$3,
            M: 'месяц',
            MM: relativeTimeWithPlural$3,
            y: 'год',
            yy: relativeTimeWithPlural$3,
        },
        meridiemParse: /ночи|утра|дня|вечера/i,
        isPM: function (input) {
            return /^(дня|вечера)$/.test(input);
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'ночи';
            } else if (hour < 12) {
                return 'утра';
            } else if (hour < 17) {
                return 'дня';
            } else {
                return 'вечера';
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(й|го|я)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'M':
                case 'd':
                case 'DDD':
                    return number + '-й';
                case 'D':
                    return number + '-го';
                case 'w':
                case 'W':
                    return number + '-я';
                default:
                    return number;
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var months$9 = [
            'جنوري',
            'فيبروري',
            'مارچ',
            'اپريل',
            'مئي',
            'جون',
            'جولاءِ',
            'آگسٽ',
            'سيپٽمبر',
            'آڪٽوبر',
            'نومبر',
            'ڊسمبر',
        ],
        days$1 = ['آچر', 'سومر', 'اڱارو', 'اربع', 'خميس', 'جمع', 'ڇنڇر'];

    hooks.defineLocale('sd', {
        months: months$9,
        monthsShort: months$9,
        weekdays: days$1,
        weekdaysShort: days$1,
        weekdaysMin: days$1,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd، D MMMM YYYY HH:mm',
        },
        meridiemParse: /صبح|شام/,
        isPM: function (input) {
            return 'شام' === input;
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'صبح';
            }
            return 'شام';
        },
        calendar: {
            sameDay: '[اڄ] LT',
            nextDay: '[سڀاڻي] LT',
            nextWeek: 'dddd [اڳين هفتي تي] LT',
            lastDay: '[ڪالهه] LT',
            lastWeek: '[گزريل هفتي] dddd [تي] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s پوء',
            past: '%s اڳ',
            s: 'چند سيڪنڊ',
            ss: '%d سيڪنڊ',
            m: 'هڪ منٽ',
            mm: '%d منٽ',
            h: 'هڪ ڪلاڪ',
            hh: '%d ڪلاڪ',
            d: 'هڪ ڏينهن',
            dd: '%d ڏينهن',
            M: 'هڪ مهينو',
            MM: '%d مهينا',
            y: 'هڪ سال',
            yy: '%d سال',
        },
        preparse: function (string) {
            return string.replace(/،/g, ',');
        },
        postformat: function (string) {
            return string.replace(/,/g, '،');
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('se', {
        months: 'ođđajagemánnu_guovvamánnu_njukčamánnu_cuoŋománnu_miessemánnu_geassemánnu_suoidnemánnu_borgemánnu_čakčamánnu_golggotmánnu_skábmamánnu_juovlamánnu'.split(
            '_'
        ),
        monthsShort:
            'ođđj_guov_njuk_cuo_mies_geas_suoi_borg_čakč_golg_skáb_juov'.split('_'),
        weekdays:
            'sotnabeaivi_vuossárga_maŋŋebárga_gaskavahkku_duorastat_bearjadat_lávvardat'.split(
                '_'
            ),
        weekdaysShort: 'sotn_vuos_maŋ_gask_duor_bear_láv'.split('_'),
        weekdaysMin: 's_v_m_g_d_b_L'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'MMMM D. [b.] YYYY',
            LLL: 'MMMM D. [b.] YYYY [ti.] HH:mm',
            LLLL: 'dddd, MMMM D. [b.] YYYY [ti.] HH:mm',
        },
        calendar: {
            sameDay: '[otne ti] LT',
            nextDay: '[ihttin ti] LT',
            nextWeek: 'dddd [ti] LT',
            lastDay: '[ikte ti] LT',
            lastWeek: '[ovddit] dddd [ti] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s geažes',
            past: 'maŋit %s',
            s: 'moadde sekunddat',
            ss: '%d sekunddat',
            m: 'okta minuhta',
            mm: '%d minuhtat',
            h: 'okta diimmu',
            hh: '%d diimmut',
            d: 'okta beaivi',
            dd: '%d beaivvit',
            M: 'okta mánnu',
            MM: '%d mánut',
            y: 'okta jahki',
            yy: '%d jagit',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    /*jshint -W100*/
    hooks.defineLocale('si', {
        months: 'ජනවාරි_පෙබරවාරි_මාර්තු_අප්‍රේල්_මැයි_ජූනි_ජූලි_අගෝස්තු_සැප්තැම්බර්_ඔක්තෝබර්_නොවැම්බර්_දෙසැම්බර්'.split(
            '_'
        ),
        monthsShort: 'ජන_පෙබ_මාර්_අප්_මැයි_ජූනි_ජූලි_අගෝ_සැප්_ඔක්_නොවැ_දෙසැ'.split(
            '_'
        ),
        weekdays:
            'ඉරිදා_සඳුදා_අඟහරුවාදා_බදාදා_බ්‍රහස්පතින්දා_සිකුරාදා_සෙනසුරාදා'.split(
                '_'
            ),
        weekdaysShort: 'ඉරි_සඳු_අඟ_බදා_බ්‍රහ_සිකු_සෙන'.split('_'),
        weekdaysMin: 'ඉ_ස_අ_බ_බ්‍ර_සි_සෙ'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'a h:mm',
            LTS: 'a h:mm:ss',
            L: 'YYYY/MM/DD',
            LL: 'YYYY MMMM D',
            LLL: 'YYYY MMMM D, a h:mm',
            LLLL: 'YYYY MMMM D [වැනි] dddd, a h:mm:ss',
        },
        calendar: {
            sameDay: '[අද] LT[ට]',
            nextDay: '[හෙට] LT[ට]',
            nextWeek: 'dddd LT[ට]',
            lastDay: '[ඊයේ] LT[ට]',
            lastWeek: '[පසුගිය] dddd LT[ට]',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%sකින්',
            past: '%sකට පෙර',
            s: 'තත්පර කිහිපය',
            ss: 'තත්පර %d',
            m: 'මිනිත්තුව',
            mm: 'මිනිත්තු %d',
            h: 'පැය',
            hh: 'පැය %d',
            d: 'දිනය',
            dd: 'දින %d',
            M: 'මාසය',
            MM: 'මාස %d',
            y: 'වසර',
            yy: 'වසර %d',
        },
        dayOfMonthOrdinalParse: /\d{1,2} වැනි/,
        ordinal: function (number) {
            return number + ' වැනි';
        },
        meridiemParse: /පෙර වරු|පස් වරු|පෙ.ව|ප.ව./,
        isPM: function (input) {
            return input === 'ප.ව.' || input === 'පස් වරු';
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours > 11) {
                return isLower ? 'ප.ව.' : 'පස් වරු';
            } else {
                return isLower ? 'පෙ.ව.' : 'පෙර වරු';
            }
        },
    });

    //! moment.js locale configuration

    var months$a =
            'január_február_marec_apríl_máj_jún_júl_august_september_október_november_december'.split(
                '_'
            ),
        monthsShort$7 = 'jan_feb_mar_apr_máj_jún_júl_aug_sep_okt_nov_dec'.split('_');
    function plural$5(n) {
        return n > 1 && n < 5;
    }
    function translate$9(number, withoutSuffix, key, isFuture) {
        var result = number + ' ';
        switch (key) {
            case 's': // a few seconds / in a few seconds / a few seconds ago
                return withoutSuffix || isFuture ? 'pár sekúnd' : 'pár sekundami';
            case 'ss': // 9 seconds / in 9 seconds / 9 seconds ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$5(number) ? 'sekundy' : 'sekúnd');
                } else {
                    return result + 'sekundami';
                }
            case 'm': // a minute / in a minute / a minute ago
                return withoutSuffix ? 'minúta' : isFuture ? 'minútu' : 'minútou';
            case 'mm': // 9 minutes / in 9 minutes / 9 minutes ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$5(number) ? 'minúty' : 'minút');
                } else {
                    return result + 'minútami';
                }
            case 'h': // an hour / in an hour / an hour ago
                return withoutSuffix ? 'hodina' : isFuture ? 'hodinu' : 'hodinou';
            case 'hh': // 9 hours / in 9 hours / 9 hours ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$5(number) ? 'hodiny' : 'hodín');
                } else {
                    return result + 'hodinami';
                }
            case 'd': // a day / in a day / a day ago
                return withoutSuffix || isFuture ? 'deň' : 'dňom';
            case 'dd': // 9 days / in 9 days / 9 days ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$5(number) ? 'dni' : 'dní');
                } else {
                    return result + 'dňami';
                }
            case 'M': // a month / in a month / a month ago
                return withoutSuffix || isFuture ? 'mesiac' : 'mesiacom';
            case 'MM': // 9 months / in 9 months / 9 months ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$5(number) ? 'mesiace' : 'mesiacov');
                } else {
                    return result + 'mesiacmi';
                }
            case 'y': // a year / in a year / a year ago
                return withoutSuffix || isFuture ? 'rok' : 'rokom';
            case 'yy': // 9 years / in 9 years / 9 years ago
                if (withoutSuffix || isFuture) {
                    return result + (plural$5(number) ? 'roky' : 'rokov');
                } else {
                    return result + 'rokmi';
                }
        }
    }

    hooks.defineLocale('sk', {
        months: months$a,
        monthsShort: monthsShort$7,
        weekdays: 'nedeľa_pondelok_utorok_streda_štvrtok_piatok_sobota'.split('_'),
        weekdaysShort: 'ne_po_ut_st_št_pi_so'.split('_'),
        weekdaysMin: 'ne_po_ut_st_št_pi_so'.split('_'),
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY H:mm',
            LLLL: 'dddd D. MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[dnes o] LT',
            nextDay: '[zajtra o] LT',
            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[v nedeľu o] LT';
                    case 1:
                    case 2:
                        return '[v] dddd [o] LT';
                    case 3:
                        return '[v stredu o] LT';
                    case 4:
                        return '[vo štvrtok o] LT';
                    case 5:
                        return '[v piatok o] LT';
                    case 6:
                        return '[v sobotu o] LT';
                }
            },
            lastDay: '[včera o] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[minulú nedeľu o] LT';
                    case 1:
                    case 2:
                        return '[minulý] dddd [o] LT';
                    case 3:
                        return '[minulú stredu o] LT';
                    case 4:
                    case 5:
                        return '[minulý] dddd [o] LT';
                    case 6:
                        return '[minulú sobotu o] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'za %s',
            past: 'pred %s',
            s: translate$9,
            ss: translate$9,
            m: translate$9,
            mm: translate$9,
            h: translate$9,
            hh: translate$9,
            d: translate$9,
            dd: translate$9,
            M: translate$9,
            MM: translate$9,
            y: translate$9,
            yy: translate$9,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function processRelativeTime$7(number, withoutSuffix, key, isFuture) {
        var result = number + ' ';
        switch (key) {
            case 's':
                return withoutSuffix || isFuture
                    ? 'nekaj sekund'
                    : 'nekaj sekundami';
            case 'ss':
                if (number === 1) {
                    result += withoutSuffix ? 'sekundo' : 'sekundi';
                } else if (number === 2) {
                    result += withoutSuffix || isFuture ? 'sekundi' : 'sekundah';
                } else if (number < 5) {
                    result += withoutSuffix || isFuture ? 'sekunde' : 'sekundah';
                } else {
                    result += 'sekund';
                }
                return result;
            case 'm':
                return withoutSuffix ? 'ena minuta' : 'eno minuto';
            case 'mm':
                if (number === 1) {
                    result += withoutSuffix ? 'minuta' : 'minuto';
                } else if (number === 2) {
                    result += withoutSuffix || isFuture ? 'minuti' : 'minutama';
                } else if (number < 5) {
                    result += withoutSuffix || isFuture ? 'minute' : 'minutami';
                } else {
                    result += withoutSuffix || isFuture ? 'minut' : 'minutami';
                }
                return result;
            case 'h':
                return withoutSuffix ? 'ena ura' : 'eno uro';
            case 'hh':
                if (number === 1) {
                    result += withoutSuffix ? 'ura' : 'uro';
                } else if (number === 2) {
                    result += withoutSuffix || isFuture ? 'uri' : 'urama';
                } else if (number < 5) {
                    result += withoutSuffix || isFuture ? 'ure' : 'urami';
                } else {
                    result += withoutSuffix || isFuture ? 'ur' : 'urami';
                }
                return result;
            case 'd':
                return withoutSuffix || isFuture ? 'en dan' : 'enim dnem';
            case 'dd':
                if (number === 1) {
                    result += withoutSuffix || isFuture ? 'dan' : 'dnem';
                } else if (number === 2) {
                    result += withoutSuffix || isFuture ? 'dni' : 'dnevoma';
                } else {
                    result += withoutSuffix || isFuture ? 'dni' : 'dnevi';
                }
                return result;
            case 'M':
                return withoutSuffix || isFuture ? 'en mesec' : 'enim mesecem';
            case 'MM':
                if (number === 1) {
                    result += withoutSuffix || isFuture ? 'mesec' : 'mesecem';
                } else if (number === 2) {
                    result += withoutSuffix || isFuture ? 'meseca' : 'mesecema';
                } else if (number < 5) {
                    result += withoutSuffix || isFuture ? 'mesece' : 'meseci';
                } else {
                    result += withoutSuffix || isFuture ? 'mesecev' : 'meseci';
                }
                return result;
            case 'y':
                return withoutSuffix || isFuture ? 'eno leto' : 'enim letom';
            case 'yy':
                if (number === 1) {
                    result += withoutSuffix || isFuture ? 'leto' : 'letom';
                } else if (number === 2) {
                    result += withoutSuffix || isFuture ? 'leti' : 'letoma';
                } else if (number < 5) {
                    result += withoutSuffix || isFuture ? 'leta' : 'leti';
                } else {
                    result += withoutSuffix || isFuture ? 'let' : 'leti';
                }
                return result;
        }
    }

    hooks.defineLocale('sl', {
        months: 'januar_februar_marec_april_maj_junij_julij_avgust_september_oktober_november_december'.split(
            '_'
        ),
        monthsShort:
            'jan._feb._mar._apr._maj._jun._jul._avg._sep._okt._nov._dec.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'nedelja_ponedeljek_torek_sreda_četrtek_petek_sobota'.split('_'),
        weekdaysShort: 'ned._pon._tor._sre._čet._pet._sob.'.split('_'),
        weekdaysMin: 'ne_po_to_sr_če_pe_so'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD. MM. YYYY',
            LL: 'D. MMMM YYYY',
            LLL: 'D. MMMM YYYY H:mm',
            LLLL: 'dddd, D. MMMM YYYY H:mm',
        },
        calendar: {
            sameDay: '[danes ob] LT',
            nextDay: '[jutri ob] LT',

            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[v] [nedeljo] [ob] LT';
                    case 3:
                        return '[v] [sredo] [ob] LT';
                    case 6:
                        return '[v] [soboto] [ob] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[v] dddd [ob] LT';
                }
            },
            lastDay: '[včeraj ob] LT',
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[prejšnjo] [nedeljo] [ob] LT';
                    case 3:
                        return '[prejšnjo] [sredo] [ob] LT';
                    case 6:
                        return '[prejšnjo] [soboto] [ob] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[prejšnji] dddd [ob] LT';
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'čez %s',
            past: 'pred %s',
            s: processRelativeTime$7,
            ss: processRelativeTime$7,
            m: processRelativeTime$7,
            mm: processRelativeTime$7,
            h: processRelativeTime$7,
            hh: processRelativeTime$7,
            d: processRelativeTime$7,
            dd: processRelativeTime$7,
            M: processRelativeTime$7,
            MM: processRelativeTime$7,
            y: processRelativeTime$7,
            yy: processRelativeTime$7,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('sq', {
        months: 'Janar_Shkurt_Mars_Prill_Maj_Qershor_Korrik_Gusht_Shtator_Tetor_Nëntor_Dhjetor'.split(
            '_'
        ),
        monthsShort: 'Jan_Shk_Mar_Pri_Maj_Qer_Kor_Gus_Sht_Tet_Nën_Dhj'.split('_'),
        weekdays: 'E Diel_E Hënë_E Martë_E Mërkurë_E Enjte_E Premte_E Shtunë'.split(
            '_'
        ),
        weekdaysShort: 'Die_Hën_Mar_Mër_Enj_Pre_Sht'.split('_'),
        weekdaysMin: 'D_H_Ma_Më_E_P_Sh'.split('_'),
        weekdaysParseExact: true,
        meridiemParse: /PD|MD/,
        isPM: function (input) {
            return input.charAt(0) === 'M';
        },
        meridiem: function (hours, minutes, isLower) {
            return hours < 12 ? 'PD' : 'MD';
        },
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Sot në] LT',
            nextDay: '[Nesër në] LT',
            nextWeek: 'dddd [në] LT',
            lastDay: '[Dje në] LT',
            lastWeek: 'dddd [e kaluar në] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'në %s',
            past: '%s më parë',
            s: 'disa sekonda',
            ss: '%d sekonda',
            m: 'një minutë',
            mm: '%d minuta',
            h: 'një orë',
            hh: '%d orë',
            d: 'një ditë',
            dd: '%d ditë',
            M: 'një muaj',
            MM: '%d muaj',
            y: 'një vit',
            yy: '%d vite',
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var translator$1 = {
        words: {
            //Different grammatical cases
            ss: ['секунда', 'секунде', 'секунди'],
            m: ['један минут', 'једног минута'],
            mm: ['минут', 'минута', 'минута'],
            h: ['један сат', 'једног сата'],
            hh: ['сат', 'сата', 'сати'],
            d: ['један дан', 'једног дана'],
            dd: ['дан', 'дана', 'дана'],
            M: ['један месец', 'једног месеца'],
            MM: ['месец', 'месеца', 'месеци'],
            y: ['једну годину', 'једне године'],
            yy: ['годину', 'године', 'година'],
        },
        correctGrammaticalCase: function (number, wordKey) {
            if (
                number % 10 >= 1 &&
                number % 10 <= 4 &&
                (number % 100 < 10 || number % 100 >= 20)
            ) {
                return number % 10 === 1 ? wordKey[0] : wordKey[1];
            }
            return wordKey[2];
        },
        translate: function (number, withoutSuffix, key, isFuture) {
            var wordKey = translator$1.words[key],
                word;

            if (key.length === 1) {
                // Nominativ
                if (key === 'y' && withoutSuffix) return 'једна година';
                return isFuture || withoutSuffix ? wordKey[0] : wordKey[1];
            }

            word = translator$1.correctGrammaticalCase(number, wordKey);
            // Nominativ
            if (key === 'yy' && withoutSuffix && word === 'годину') {
                return number + ' година';
            }

            return number + ' ' + word;
        },
    };

    hooks.defineLocale('sr-cyrl', {
        months: 'јануар_фебруар_март_април_мај_јун_јул_август_септембар_октобар_новембар_децембар'.split(
            '_'
        ),
        monthsShort:
            'јан._феб._мар._апр._мај_јун_јул_авг._сеп._окт._нов._дец.'.split('_'),
        monthsParseExact: true,
        weekdays: 'недеља_понедељак_уторак_среда_четвртак_петак_субота'.split('_'),
        weekdaysShort: 'нед._пон._уто._сре._чет._пет._суб.'.split('_'),
        weekdaysMin: 'не_по_ут_ср_че_пе_су'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'D. M. YYYY.',
            LL: 'D. MMMM YYYY.',
            LLL: 'D. MMMM YYYY. H:mm',
            LLLL: 'dddd, D. MMMM YYYY. H:mm',
        },
        calendar: {
            sameDay: '[данас у] LT',
            nextDay: '[сутра у] LT',
            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[у] [недељу] [у] LT';
                    case 3:
                        return '[у] [среду] [у] LT';
                    case 6:
                        return '[у] [суботу] [у] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[у] dddd [у] LT';
                }
            },
            lastDay: '[јуче у] LT',
            lastWeek: function () {
                var lastWeekDays = [
                    '[прошле] [недеље] [у] LT',
                    '[прошлог] [понедељка] [у] LT',
                    '[прошлог] [уторка] [у] LT',
                    '[прошле] [среде] [у] LT',
                    '[прошлог] [четвртка] [у] LT',
                    '[прошлог] [петка] [у] LT',
                    '[прошле] [суботе] [у] LT',
                ];
                return lastWeekDays[this.day()];
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'за %s',
            past: 'пре %s',
            s: 'неколико секунди',
            ss: translator$1.translate,
            m: translator$1.translate,
            mm: translator$1.translate,
            h: translator$1.translate,
            hh: translator$1.translate,
            d: translator$1.translate,
            dd: translator$1.translate,
            M: translator$1.translate,
            MM: translator$1.translate,
            y: translator$1.translate,
            yy: translator$1.translate,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 1st is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var translator$2 = {
        words: {
            //Different grammatical cases
            ss: ['sekunda', 'sekunde', 'sekundi'],
            m: ['jedan minut', 'jednog minuta'],
            mm: ['minut', 'minuta', 'minuta'],
            h: ['jedan sat', 'jednog sata'],
            hh: ['sat', 'sata', 'sati'],
            d: ['jedan dan', 'jednog dana'],
            dd: ['dan', 'dana', 'dana'],
            M: ['jedan mesec', 'jednog meseca'],
            MM: ['mesec', 'meseca', 'meseci'],
            y: ['jednu godinu', 'jedne godine'],
            yy: ['godinu', 'godine', 'godina'],
        },
        correctGrammaticalCase: function (number, wordKey) {
            if (
                number % 10 >= 1 &&
                number % 10 <= 4 &&
                (number % 100 < 10 || number % 100 >= 20)
            ) {
                return number % 10 === 1 ? wordKey[0] : wordKey[1];
            }
            return wordKey[2];
        },
        translate: function (number, withoutSuffix, key, isFuture) {
            var wordKey = translator$2.words[key],
                word;

            if (key.length === 1) {
                // Nominativ
                if (key === 'y' && withoutSuffix) return 'jedna godina';
                return isFuture || withoutSuffix ? wordKey[0] : wordKey[1];
            }

            word = translator$2.correctGrammaticalCase(number, wordKey);
            // Nominativ
            if (key === 'yy' && withoutSuffix && word === 'godinu') {
                return number + ' godina';
            }

            return number + ' ' + word;
        },
    };

    hooks.defineLocale('sr', {
        months: 'januar_februar_mart_april_maj_jun_jul_avgust_septembar_oktobar_novembar_decembar'.split(
            '_'
        ),
        monthsShort:
            'jan._feb._mar._apr._maj_jun_jul_avg._sep._okt._nov._dec.'.split('_'),
        monthsParseExact: true,
        weekdays: 'nedelja_ponedeljak_utorak_sreda_četvrtak_petak_subota'.split(
            '_'
        ),
        weekdaysShort: 'ned._pon._uto._sre._čet._pet._sub.'.split('_'),
        weekdaysMin: 'ne_po_ut_sr_če_pe_su'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'D. M. YYYY.',
            LL: 'D. MMMM YYYY.',
            LLL: 'D. MMMM YYYY. H:mm',
            LLLL: 'dddd, D. MMMM YYYY. H:mm',
        },
        calendar: {
            sameDay: '[danas u] LT',
            nextDay: '[sutra u] LT',
            nextWeek: function () {
                switch (this.day()) {
                    case 0:
                        return '[u] [nedelju] [u] LT';
                    case 3:
                        return '[u] [sredu] [u] LT';
                    case 6:
                        return '[u] [subotu] [u] LT';
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                        return '[u] dddd [u] LT';
                }
            },
            lastDay: '[juče u] LT',
            lastWeek: function () {
                var lastWeekDays = [
                    '[prošle] [nedelje] [u] LT',
                    '[prošlog] [ponedeljka] [u] LT',
                    '[prošlog] [utorka] [u] LT',
                    '[prošle] [srede] [u] LT',
                    '[prošlog] [četvrtka] [u] LT',
                    '[prošlog] [petka] [u] LT',
                    '[prošle] [subote] [u] LT',
                ];
                return lastWeekDays[this.day()];
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'za %s',
            past: 'pre %s',
            s: 'nekoliko sekundi',
            ss: translator$2.translate,
            m: translator$2.translate,
            mm: translator$2.translate,
            h: translator$2.translate,
            hh: translator$2.translate,
            d: translator$2.translate,
            dd: translator$2.translate,
            M: translator$2.translate,
            MM: translator$2.translate,
            y: translator$2.translate,
            yy: translator$2.translate,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ss', {
        months: "Bhimbidvwane_Indlovana_Indlov'lenkhulu_Mabasa_Inkhwekhweti_Inhlaba_Kholwane_Ingci_Inyoni_Imphala_Lweti_Ingongoni".split(
            '_'
        ),
        monthsShort: 'Bhi_Ina_Inu_Mab_Ink_Inh_Kho_Igc_Iny_Imp_Lwe_Igo'.split('_'),
        weekdays:
            'Lisontfo_Umsombuluko_Lesibili_Lesitsatfu_Lesine_Lesihlanu_Umgcibelo'.split(
                '_'
            ),
        weekdaysShort: 'Lis_Umb_Lsb_Les_Lsi_Lsh_Umg'.split('_'),
        weekdaysMin: 'Li_Us_Lb_Lt_Ls_Lh_Ug'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY h:mm A',
            LLLL: 'dddd, D MMMM YYYY h:mm A',
        },
        calendar: {
            sameDay: '[Namuhla nga] LT',
            nextDay: '[Kusasa nga] LT',
            nextWeek: 'dddd [nga] LT',
            lastDay: '[Itolo nga] LT',
            lastWeek: 'dddd [leliphelile] [nga] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'nga %s',
            past: 'wenteka nga %s',
            s: 'emizuzwana lomcane',
            ss: '%d mzuzwana',
            m: 'umzuzu',
            mm: '%d emizuzu',
            h: 'lihora',
            hh: '%d emahora',
            d: 'lilanga',
            dd: '%d emalanga',
            M: 'inyanga',
            MM: '%d tinyanga',
            y: 'umnyaka',
            yy: '%d iminyaka',
        },
        meridiemParse: /ekuseni|emini|entsambama|ebusuku/,
        meridiem: function (hours, minutes, isLower) {
            if (hours < 11) {
                return 'ekuseni';
            } else if (hours < 15) {
                return 'emini';
            } else if (hours < 19) {
                return 'entsambama';
            } else {
                return 'ebusuku';
            }
        },
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'ekuseni') {
                return hour;
            } else if (meridiem === 'emini') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'entsambama' || meridiem === 'ebusuku') {
                if (hour === 0) {
                    return 0;
                }
                return hour + 12;
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}/,
        ordinal: '%d',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('sv', {
        months: 'januari_februari_mars_april_maj_juni_juli_augusti_september_oktober_november_december'.split(
            '_'
        ),
        monthsShort: 'jan_feb_mar_apr_maj_jun_jul_aug_sep_okt_nov_dec'.split('_'),
        weekdays: 'söndag_måndag_tisdag_onsdag_torsdag_fredag_lördag'.split('_'),
        weekdaysShort: 'sön_mån_tis_ons_tor_fre_lör'.split('_'),
        weekdaysMin: 'sö_må_ti_on_to_fr_lö'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY-MM-DD',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY [kl.] HH:mm',
            LLLL: 'dddd D MMMM YYYY [kl.] HH:mm',
            lll: 'D MMM YYYY HH:mm',
            llll: 'ddd D MMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Idag] LT',
            nextDay: '[Imorgon] LT',
            lastDay: '[Igår] LT',
            nextWeek: '[På] dddd LT',
            lastWeek: '[I] dddd[s] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'om %s',
            past: 'för %s sedan',
            s: 'några sekunder',
            ss: '%d sekunder',
            m: 'en minut',
            mm: '%d minuter',
            h: 'en timme',
            hh: '%d timmar',
            d: 'en dag',
            dd: '%d dagar',
            M: 'en månad',
            MM: '%d månader',
            y: 'ett år',
            yy: '%d år',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(\:e|\:a)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? ':e'
                        : b === 1
                        ? ':a'
                        : b === 2
                        ? ':a'
                        : b === 3
                        ? ':e'
                        : ':e';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('sw', {
        months: 'Januari_Februari_Machi_Aprili_Mei_Juni_Julai_Agosti_Septemba_Oktoba_Novemba_Desemba'.split(
            '_'
        ),
        monthsShort: 'Jan_Feb_Mac_Apr_Mei_Jun_Jul_Ago_Sep_Okt_Nov_Des'.split('_'),
        weekdays:
            'Jumapili_Jumatatu_Jumanne_Jumatano_Alhamisi_Ijumaa_Jumamosi'.split(
                '_'
            ),
        weekdaysShort: 'Jpl_Jtat_Jnne_Jtan_Alh_Ijm_Jmos'.split('_'),
        weekdaysMin: 'J2_J3_J4_J5_Al_Ij_J1'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'hh:mm A',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[leo saa] LT',
            nextDay: '[kesho saa] LT',
            nextWeek: '[wiki ijayo] dddd [saat] LT',
            lastDay: '[jana] LT',
            lastWeek: '[wiki iliyopita] dddd [saat] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s baadaye',
            past: 'tokea %s',
            s: 'hivi punde',
            ss: 'sekunde %d',
            m: 'dakika moja',
            mm: 'dakika %d',
            h: 'saa limoja',
            hh: 'masaa %d',
            d: 'siku moja',
            dd: 'siku %d',
            M: 'mwezi mmoja',
            MM: 'miezi %d',
            y: 'mwaka mmoja',
            yy: 'miaka %d',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var symbolMap$g = {
            1: '௧',
            2: '௨',
            3: '௩',
            4: '௪',
            5: '௫',
            6: '௬',
            7: '௭',
            8: '௮',
            9: '௯',
            0: '௦',
        },
        numberMap$f = {
            '௧': '1',
            '௨': '2',
            '௩': '3',
            '௪': '4',
            '௫': '5',
            '௬': '6',
            '௭': '7',
            '௮': '8',
            '௯': '9',
            '௦': '0',
        };

    hooks.defineLocale('ta', {
        months: 'ஜனவரி_பிப்ரவரி_மார்ச்_ஏப்ரல்_மே_ஜூன்_ஜூலை_ஆகஸ்ட்_செப்டெம்பர்_அக்டோபர்_நவம்பர்_டிசம்பர்'.split(
            '_'
        ),
        monthsShort:
            'ஜனவரி_பிப்ரவரி_மார்ச்_ஏப்ரல்_மே_ஜூன்_ஜூலை_ஆகஸ்ட்_செப்டெம்பர்_அக்டோபர்_நவம்பர்_டிசம்பர்'.split(
                '_'
            ),
        weekdays:
            'ஞாயிற்றுக்கிழமை_திங்கட்கிழமை_செவ்வாய்கிழமை_புதன்கிழமை_வியாழக்கிழமை_வெள்ளிக்கிழமை_சனிக்கிழமை'.split(
                '_'
            ),
        weekdaysShort: 'ஞாயிறு_திங்கள்_செவ்வாய்_புதன்_வியாழன்_வெள்ளி_சனி'.split(
            '_'
        ),
        weekdaysMin: 'ஞா_தி_செ_பு_வி_வெ_ச'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, HH:mm',
            LLLL: 'dddd, D MMMM YYYY, HH:mm',
        },
        calendar: {
            sameDay: '[இன்று] LT',
            nextDay: '[நாளை] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[நேற்று] LT',
            lastWeek: '[கடந்த வாரம்] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s இல்',
            past: '%s முன்',
            s: 'ஒரு சில விநாடிகள்',
            ss: '%d விநாடிகள்',
            m: 'ஒரு நிமிடம்',
            mm: '%d நிமிடங்கள்',
            h: 'ஒரு மணி நேரம்',
            hh: '%d மணி நேரம்',
            d: 'ஒரு நாள்',
            dd: '%d நாட்கள்',
            M: 'ஒரு மாதம்',
            MM: '%d மாதங்கள்',
            y: 'ஒரு வருடம்',
            yy: '%d ஆண்டுகள்',
        },
        dayOfMonthOrdinalParse: /\d{1,2}வது/,
        ordinal: function (number) {
            return number + 'வது';
        },
        preparse: function (string) {
            return string.replace(/[௧௨௩௪௫௬௭௮௯௦]/g, function (match) {
                return numberMap$f[match];
            });
        },
        postformat: function (string) {
            return string.replace(/\d/g, function (match) {
                return symbolMap$g[match];
            });
        },
        // refer http://ta.wikipedia.org/s/1er1
        meridiemParse: /யாமம்|வைகறை|காலை|நண்பகல்|எற்பாடு|மாலை/,
        meridiem: function (hour, minute, isLower) {
            if (hour < 2) {
                return ' யாமம்';
            } else if (hour < 6) {
                return ' வைகறை'; // வைகறை
            } else if (hour < 10) {
                return ' காலை'; // காலை
            } else if (hour < 14) {
                return ' நண்பகல்'; // நண்பகல்
            } else if (hour < 18) {
                return ' எற்பாடு'; // எற்பாடு
            } else if (hour < 22) {
                return ' மாலை'; // மாலை
            } else {
                return ' யாமம்';
            }
        },
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'யாமம்') {
                return hour < 2 ? hour : hour + 12;
            } else if (meridiem === 'வைகறை' || meridiem === 'காலை') {
                return hour;
            } else if (meridiem === 'நண்பகல்') {
                return hour >= 10 ? hour : hour + 12;
            } else {
                return hour + 12;
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('te', {
        months: 'జనవరి_ఫిబ్రవరి_మార్చి_ఏప్రిల్_మే_జూన్_జులై_ఆగస్టు_సెప్టెంబర్_అక్టోబర్_నవంబర్_డిసెంబర్'.split(
            '_'
        ),
        monthsShort:
            'జన._ఫిబ్ర._మార్చి_ఏప్రి._మే_జూన్_జులై_ఆగ._సెప్._అక్టో._నవ._డిసె.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays:
            'ఆదివారం_సోమవారం_మంగళవారం_బుధవారం_గురువారం_శుక్రవారం_శనివారం'.split(
                '_'
            ),
        weekdaysShort: 'ఆది_సోమ_మంగళ_బుధ_గురు_శుక్ర_శని'.split('_'),
        weekdaysMin: 'ఆ_సో_మం_బు_గు_శు_శ'.split('_'),
        longDateFormat: {
            LT: 'A h:mm',
            LTS: 'A h:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY, A h:mm',
            LLLL: 'dddd, D MMMM YYYY, A h:mm',
        },
        calendar: {
            sameDay: '[నేడు] LT',
            nextDay: '[రేపు] LT',
            nextWeek: 'dddd, LT',
            lastDay: '[నిన్న] LT',
            lastWeek: '[గత] dddd, LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s లో',
            past: '%s క్రితం',
            s: 'కొన్ని క్షణాలు',
            ss: '%d సెకన్లు',
            m: 'ఒక నిమిషం',
            mm: '%d నిమిషాలు',
            h: 'ఒక గంట',
            hh: '%d గంటలు',
            d: 'ఒక రోజు',
            dd: '%d రోజులు',
            M: 'ఒక నెల',
            MM: '%d నెలలు',
            y: 'ఒక సంవత్సరం',
            yy: '%d సంవత్సరాలు',
        },
        dayOfMonthOrdinalParse: /\d{1,2}వ/,
        ordinal: '%dవ',
        meridiemParse: /రాత్రి|ఉదయం|మధ్యాహ్నం|సాయంత్రం/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'రాత్రి') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'ఉదయం') {
                return hour;
            } else if (meridiem === 'మధ్యాహ్నం') {
                return hour >= 10 ? hour : hour + 12;
            } else if (meridiem === 'సాయంత్రం') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'రాత్రి';
            } else if (hour < 10) {
                return 'ఉదయం';
            } else if (hour < 17) {
                return 'మధ్యాహ్నం';
            } else if (hour < 20) {
                return 'సాయంత్రం';
            } else {
                return 'రాత్రి';
            }
        },
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6, // The week that contains Jan 6th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('tet', {
        months: 'Janeiru_Fevereiru_Marsu_Abril_Maiu_Juñu_Jullu_Agustu_Setembru_Outubru_Novembru_Dezembru'.split(
            '_'
        ),
        monthsShort: 'Jan_Fev_Mar_Abr_Mai_Jun_Jul_Ago_Set_Out_Nov_Dez'.split('_'),
        weekdays: 'Domingu_Segunda_Tersa_Kuarta_Kinta_Sesta_Sabadu'.split('_'),
        weekdaysShort: 'Dom_Seg_Ters_Kua_Kint_Sest_Sab'.split('_'),
        weekdaysMin: 'Do_Seg_Te_Ku_Ki_Ses_Sa'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Ohin iha] LT',
            nextDay: '[Aban iha] LT',
            nextWeek: 'dddd [iha] LT',
            lastDay: '[Horiseik iha] LT',
            lastWeek: 'dddd [semana kotuk] [iha] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'iha %s',
            past: '%s liuba',
            s: 'segundu balun',
            ss: 'segundu %d',
            m: 'minutu ida',
            mm: 'minutu %d',
            h: 'oras ida',
            hh: 'oras %d',
            d: 'loron ida',
            dd: 'loron %d',
            M: 'fulan ida',
            MM: 'fulan %d',
            y: 'tinan ida',
            yy: 'tinan %d',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var suffixes$3 = {
        0: '-ум',
        1: '-ум',
        2: '-юм',
        3: '-юм',
        4: '-ум',
        5: '-ум',
        6: '-ум',
        7: '-ум',
        8: '-ум',
        9: '-ум',
        10: '-ум',
        12: '-ум',
        13: '-ум',
        20: '-ум',
        30: '-юм',
        40: '-ум',
        50: '-ум',
        60: '-ум',
        70: '-ум',
        80: '-ум',
        90: '-ум',
        100: '-ум',
    };

    hooks.defineLocale('tg', {
        months: {
            format: 'январи_феврали_марти_апрели_майи_июни_июли_августи_сентябри_октябри_ноябри_декабри'.split(
                '_'
            ),
            standalone:
                'январ_феврал_март_апрел_май_июн_июл_август_сентябр_октябр_ноябр_декабр'.split(
                    '_'
                ),
        },
        monthsShort: 'янв_фев_мар_апр_май_июн_июл_авг_сен_окт_ноя_дек'.split('_'),
        weekdays: 'якшанбе_душанбе_сешанбе_чоршанбе_панҷшанбе_ҷумъа_шанбе'.split(
            '_'
        ),
        weekdaysShort: 'яшб_дшб_сшб_чшб_пшб_ҷум_шнб'.split('_'),
        weekdaysMin: 'яш_дш_сш_чш_пш_ҷм_шб'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Имрӯз соати] LT',
            nextDay: '[Фардо соати] LT',
            lastDay: '[Дирӯз соати] LT',
            nextWeek: 'dddd[и] [ҳафтаи оянда соати] LT',
            lastWeek: 'dddd[и] [ҳафтаи гузашта соати] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'баъди %s',
            past: '%s пеш',
            s: 'якчанд сония',
            m: 'як дақиқа',
            mm: '%d дақиқа',
            h: 'як соат',
            hh: '%d соат',
            d: 'як рӯз',
            dd: '%d рӯз',
            M: 'як моҳ',
            MM: '%d моҳ',
            y: 'як сол',
            yy: '%d сол',
        },
        meridiemParse: /шаб|субҳ|рӯз|бегоҳ/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'шаб') {
                return hour < 4 ? hour : hour + 12;
            } else if (meridiem === 'субҳ') {
                return hour;
            } else if (meridiem === 'рӯз') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'бегоҳ') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'шаб';
            } else if (hour < 11) {
                return 'субҳ';
            } else if (hour < 16) {
                return 'рӯз';
            } else if (hour < 19) {
                return 'бегоҳ';
            } else {
                return 'шаб';
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(ум|юм)/,
        ordinal: function (number) {
            var a = number % 10,
                b = number >= 100 ? 100 : null;
            return number + (suffixes$3[number] || suffixes$3[a] || suffixes$3[b]);
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 1th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('th', {
        months: 'มกราคม_กุมภาพันธ์_มีนาคม_เมษายน_พฤษภาคม_มิถุนายน_กรกฎาคม_สิงหาคม_กันยายน_ตุลาคม_พฤศจิกายน_ธันวาคม'.split(
            '_'
        ),
        monthsShort:
            'ม.ค._ก.พ._มี.ค._เม.ย._พ.ค._มิ.ย._ก.ค._ส.ค._ก.ย._ต.ค._พ.ย._ธ.ค.'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'อาทิตย์_จันทร์_อังคาร_พุธ_พฤหัสบดี_ศุกร์_เสาร์'.split('_'),
        weekdaysShort: 'อาทิตย์_จันทร์_อังคาร_พุธ_พฤหัส_ศุกร์_เสาร์'.split('_'), // yes, three characters difference
        weekdaysMin: 'อา._จ._อ._พ._พฤ._ศ._ส.'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'H:mm',
            LTS: 'H:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY เวลา H:mm',
            LLLL: 'วันddddที่ D MMMM YYYY เวลา H:mm',
        },
        meridiemParse: /ก่อนเที่ยง|หลังเที่ยง/,
        isPM: function (input) {
            return input === 'หลังเที่ยง';
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'ก่อนเที่ยง';
            } else {
                return 'หลังเที่ยง';
            }
        },
        calendar: {
            sameDay: '[วันนี้ เวลา] LT',
            nextDay: '[พรุ่งนี้ เวลา] LT',
            nextWeek: 'dddd[หน้า เวลา] LT',
            lastDay: '[เมื่อวานนี้ เวลา] LT',
            lastWeek: '[วัน]dddd[ที่แล้ว เวลา] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'อีก %s',
            past: '%sที่แล้ว',
            s: 'ไม่กี่วินาที',
            ss: '%d วินาที',
            m: '1 นาที',
            mm: '%d นาที',
            h: '1 ชั่วโมง',
            hh: '%d ชั่วโมง',
            d: '1 วัน',
            dd: '%d วัน',
            w: '1 สัปดาห์',
            ww: '%d สัปดาห์',
            M: '1 เดือน',
            MM: '%d เดือน',
            y: '1 ปี',
            yy: '%d ปี',
        },
    });

    //! moment.js locale configuration

    var suffixes$4 = {
        1: "'inji",
        5: "'inji",
        8: "'inji",
        70: "'inji",
        80: "'inji",
        2: "'nji",
        7: "'nji",
        20: "'nji",
        50: "'nji",
        3: "'ünji",
        4: "'ünji",
        100: "'ünji",
        6: "'njy",
        9: "'unjy",
        10: "'unjy",
        30: "'unjy",
        60: "'ynjy",
        90: "'ynjy",
    };

    hooks.defineLocale('tk', {
        months: 'Ýanwar_Fewral_Mart_Aprel_Maý_Iýun_Iýul_Awgust_Sentýabr_Oktýabr_Noýabr_Dekabr'.split(
            '_'
        ),
        monthsShort: 'Ýan_Few_Mar_Apr_Maý_Iýn_Iýl_Awg_Sen_Okt_Noý_Dek'.split('_'),
        weekdays: 'Ýekşenbe_Duşenbe_Sişenbe_Çarşenbe_Penşenbe_Anna_Şenbe'.split(
            '_'
        ),
        weekdaysShort: 'Ýek_Duş_Siş_Çar_Pen_Ann_Şen'.split('_'),
        weekdaysMin: 'Ýk_Dş_Sş_Çr_Pn_An_Şn'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[bugün sagat] LT',
            nextDay: '[ertir sagat] LT',
            nextWeek: '[indiki] dddd [sagat] LT',
            lastDay: '[düýn] LT',
            lastWeek: '[geçen] dddd [sagat] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s soň',
            past: '%s öň',
            s: 'birnäçe sekunt',
            m: 'bir minut',
            mm: '%d minut',
            h: 'bir sagat',
            hh: '%d sagat',
            d: 'bir gün',
            dd: '%d gün',
            M: 'bir aý',
            MM: '%d aý',
            y: 'bir ýyl',
            yy: '%d ýyl',
        },
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'Do':
                case 'DD':
                    return number;
                default:
                    if (number === 0) {
                        // special case for zero
                        return number + "'unjy";
                    }
                    var a = number % 10,
                        b = (number % 100) - a,
                        c = number >= 100 ? 100 : null;
                    return number + (suffixes$4[a] || suffixes$4[b] || suffixes$4[c]);
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('tl-ph', {
        months: 'Enero_Pebrero_Marso_Abril_Mayo_Hunyo_Hulyo_Agosto_Setyembre_Oktubre_Nobyembre_Disyembre'.split(
            '_'
        ),
        monthsShort: 'Ene_Peb_Mar_Abr_May_Hun_Hul_Ago_Set_Okt_Nob_Dis'.split('_'),
        weekdays: 'Linggo_Lunes_Martes_Miyerkules_Huwebes_Biyernes_Sabado'.split(
            '_'
        ),
        weekdaysShort: 'Lin_Lun_Mar_Miy_Huw_Biy_Sab'.split('_'),
        weekdaysMin: 'Li_Lu_Ma_Mi_Hu_Bi_Sab'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'MM/D/YYYY',
            LL: 'MMMM D, YYYY',
            LLL: 'MMMM D, YYYY HH:mm',
            LLLL: 'dddd, MMMM DD, YYYY HH:mm',
        },
        calendar: {
            sameDay: 'LT [ngayong araw]',
            nextDay: '[Bukas ng] LT',
            nextWeek: 'LT [sa susunod na] dddd',
            lastDay: 'LT [kahapon]',
            lastWeek: 'LT [noong nakaraang] dddd',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'sa loob ng %s',
            past: '%s ang nakalipas',
            s: 'ilang segundo',
            ss: '%d segundo',
            m: 'isang minuto',
            mm: '%d minuto',
            h: 'isang oras',
            hh: '%d oras',
            d: 'isang araw',
            dd: '%d araw',
            M: 'isang buwan',
            MM: '%d buwan',
            y: 'isang taon',
            yy: '%d taon',
        },
        dayOfMonthOrdinalParse: /\d{1,2}/,
        ordinal: function (number) {
            return number;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var numbersNouns = 'pagh_wa’_cha’_wej_loS_vagh_jav_Soch_chorgh_Hut'.split('_');

    function translateFuture(output) {
        var time = output;
        time =
            output.indexOf('jaj') !== -1
                ? time.slice(0, -3) + 'leS'
                : output.indexOf('jar') !== -1
                ? time.slice(0, -3) + 'waQ'
                : output.indexOf('DIS') !== -1
                ? time.slice(0, -3) + 'nem'
                : time + ' pIq';
        return time;
    }

    function translatePast(output) {
        var time = output;
        time =
            output.indexOf('jaj') !== -1
                ? time.slice(0, -3) + 'Hu’'
                : output.indexOf('jar') !== -1
                ? time.slice(0, -3) + 'wen'
                : output.indexOf('DIS') !== -1
                ? time.slice(0, -3) + 'ben'
                : time + ' ret';
        return time;
    }

    function translate$a(number, withoutSuffix, string, isFuture) {
        var numberNoun = numberAsNoun(number);
        switch (string) {
            case 'ss':
                return numberNoun + ' lup';
            case 'mm':
                return numberNoun + ' tup';
            case 'hh':
                return numberNoun + ' rep';
            case 'dd':
                return numberNoun + ' jaj';
            case 'MM':
                return numberNoun + ' jar';
            case 'yy':
                return numberNoun + ' DIS';
        }
    }

    function numberAsNoun(number) {
        var hundred = Math.floor((number % 1000) / 100),
            ten = Math.floor((number % 100) / 10),
            one = number % 10,
            word = '';
        if (hundred > 0) {
            word += numbersNouns[hundred] + 'vatlh';
        }
        if (ten > 0) {
            word += (word !== '' ? ' ' : '') + numbersNouns[ten] + 'maH';
        }
        if (one > 0) {
            word += (word !== '' ? ' ' : '') + numbersNouns[one];
        }
        return word === '' ? 'pagh' : word;
    }

    hooks.defineLocale('tlh', {
        months: 'tera’ jar wa’_tera’ jar cha’_tera’ jar wej_tera’ jar loS_tera’ jar vagh_tera’ jar jav_tera’ jar Soch_tera’ jar chorgh_tera’ jar Hut_tera’ jar wa’maH_tera’ jar wa’maH wa’_tera’ jar wa’maH cha’'.split(
            '_'
        ),
        monthsShort:
            'jar wa’_jar cha’_jar wej_jar loS_jar vagh_jar jav_jar Soch_jar chorgh_jar Hut_jar wa’maH_jar wa’maH wa’_jar wa’maH cha’'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'lojmItjaj_DaSjaj_povjaj_ghItlhjaj_loghjaj_buqjaj_ghInjaj'.split(
            '_'
        ),
        weekdaysShort:
            'lojmItjaj_DaSjaj_povjaj_ghItlhjaj_loghjaj_buqjaj_ghInjaj'.split('_'),
        weekdaysMin:
            'lojmItjaj_DaSjaj_povjaj_ghItlhjaj_loghjaj_buqjaj_ghInjaj'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[DaHjaj] LT',
            nextDay: '[wa’leS] LT',
            nextWeek: 'LLL',
            lastDay: '[wa’Hu’] LT',
            lastWeek: 'LLL',
            sameElse: 'L',
        },
        relativeTime: {
            future: translateFuture,
            past: translatePast,
            s: 'puS lup',
            ss: translate$a,
            m: 'wa’ tup',
            mm: translate$a,
            h: 'wa’ rep',
            hh: translate$a,
            d: 'wa’ jaj',
            dd: translate$a,
            M: 'wa’ jar',
            MM: translate$a,
            y: 'wa’ DIS',
            yy: translate$a,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var suffixes$5 = {
        1: "'inci",
        5: "'inci",
        8: "'inci",
        70: "'inci",
        80: "'inci",
        2: "'nci",
        7: "'nci",
        20: "'nci",
        50: "'nci",
        3: "'üncü",
        4: "'üncü",
        100: "'üncü",
        6: "'ncı",
        9: "'uncu",
        10: "'uncu",
        30: "'uncu",
        60: "'ıncı",
        90: "'ıncı",
    };

    hooks.defineLocale('tr', {
        months: 'Ocak_Şubat_Mart_Nisan_Mayıs_Haziran_Temmuz_Ağustos_Eylül_Ekim_Kasım_Aralık'.split(
            '_'
        ),
        monthsShort: 'Oca_Şub_Mar_Nis_May_Haz_Tem_Ağu_Eyl_Eki_Kas_Ara'.split('_'),
        weekdays: 'Pazar_Pazartesi_Salı_Çarşamba_Perşembe_Cuma_Cumartesi'.split(
            '_'
        ),
        weekdaysShort: 'Paz_Pzt_Sal_Çar_Per_Cum_Cmt'.split('_'),
        weekdaysMin: 'Pz_Pt_Sa_Ça_Pe_Cu_Ct'.split('_'),
        meridiem: function (hours, minutes, isLower) {
            if (hours < 12) {
                return isLower ? 'öö' : 'ÖÖ';
            } else {
                return isLower ? 'ös' : 'ÖS';
            }
        },
        meridiemParse: /öö|ÖÖ|ös|ÖS/,
        isPM: function (input) {
            return input === 'ös' || input === 'ÖS';
        },
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[bugün saat] LT',
            nextDay: '[yarın saat] LT',
            nextWeek: '[gelecek] dddd [saat] LT',
            lastDay: '[dün] LT',
            lastWeek: '[geçen] dddd [saat] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s sonra',
            past: '%s önce',
            s: 'birkaç saniye',
            ss: '%d saniye',
            m: 'bir dakika',
            mm: '%d dakika',
            h: 'bir saat',
            hh: '%d saat',
            d: 'bir gün',
            dd: '%d gün',
            w: 'bir hafta',
            ww: '%d hafta',
            M: 'bir ay',
            MM: '%d ay',
            y: 'bir yıl',
            yy: '%d yıl',
        },
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'Do':
                case 'DD':
                    return number;
                default:
                    if (number === 0) {
                        // special case for zero
                        return number + "'ıncı";
                    }
                    var a = number % 10,
                        b = (number % 100) - a,
                        c = number >= 100 ? 100 : null;
                    return number + (suffixes$5[a] || suffixes$5[b] || suffixes$5[c]);
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    // After the year there should be a slash and the amount of years since December 26, 1979 in Roman numerals.
    // This is currently too difficult (maybe even impossible) to add.
    hooks.defineLocale('tzl', {
        months: 'Januar_Fevraglh_Març_Avrïu_Mai_Gün_Julia_Guscht_Setemvar_Listopäts_Noemvar_Zecemvar'.split(
            '_'
        ),
        monthsShort: 'Jan_Fev_Mar_Avr_Mai_Gün_Jul_Gus_Set_Lis_Noe_Zec'.split('_'),
        weekdays: 'Súladi_Lúneçi_Maitzi_Márcuri_Xhúadi_Viénerçi_Sáturi'.split('_'),
        weekdaysShort: 'Súl_Lún_Mai_Már_Xhú_Vié_Sát'.split('_'),
        weekdaysMin: 'Sú_Lú_Ma_Má_Xh_Vi_Sá'.split('_'),
        longDateFormat: {
            LT: 'HH.mm',
            LTS: 'HH.mm.ss',
            L: 'DD.MM.YYYY',
            LL: 'D. MMMM [dallas] YYYY',
            LLL: 'D. MMMM [dallas] YYYY HH.mm',
            LLLL: 'dddd, [li] D. MMMM [dallas] YYYY HH.mm',
        },
        meridiemParse: /d\'o|d\'a/i,
        isPM: function (input) {
            return "d'o" === input.toLowerCase();
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours > 11) {
                return isLower ? "d'o" : "D'O";
            } else {
                return isLower ? "d'a" : "D'A";
            }
        },
        calendar: {
            sameDay: '[oxhi à] LT',
            nextDay: '[demà à] LT',
            nextWeek: 'dddd [à] LT',
            lastDay: '[ieiri à] LT',
            lastWeek: '[sür el] dddd [lasteu à] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'osprei %s',
            past: 'ja%s',
            s: processRelativeTime$8,
            ss: processRelativeTime$8,
            m: processRelativeTime$8,
            mm: processRelativeTime$8,
            h: processRelativeTime$8,
            hh: processRelativeTime$8,
            d: processRelativeTime$8,
            dd: processRelativeTime$8,
            M: processRelativeTime$8,
            MM: processRelativeTime$8,
            y: processRelativeTime$8,
            yy: processRelativeTime$8,
        },
        dayOfMonthOrdinalParse: /\d{1,2}\./,
        ordinal: '%d.',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    function processRelativeTime$8(number, withoutSuffix, key, isFuture) {
        var format = {
            s: ['viensas secunds', "'iensas secunds"],
            ss: [number + ' secunds', '' + number + ' secunds'],
            m: ["'n míut", "'iens míut"],
            mm: [number + ' míuts', '' + number + ' míuts'],
            h: ["'n þora", "'iensa þora"],
            hh: [number + ' þoras', '' + number + ' þoras'],
            d: ["'n ziua", "'iensa ziua"],
            dd: [number + ' ziuas', '' + number + ' ziuas'],
            M: ["'n mes", "'iens mes"],
            MM: [number + ' mesen', '' + number + ' mesen'],
            y: ["'n ar", "'iens ar"],
            yy: [number + ' ars', '' + number + ' ars'],
        };
        return isFuture
            ? format[key][0]
            : withoutSuffix
            ? format[key][0]
            : format[key][1];
    }

    //! moment.js locale configuration

    hooks.defineLocale('tzm-latn', {
        months: 'innayr_brˤayrˤ_marˤsˤ_ibrir_mayyw_ywnyw_ywlywz_ɣwšt_šwtanbir_ktˤwbrˤ_nwwanbir_dwjnbir'.split(
            '_'
        ),
        monthsShort:
            'innayr_brˤayrˤ_marˤsˤ_ibrir_mayyw_ywnyw_ywlywz_ɣwšt_šwtanbir_ktˤwbrˤ_nwwanbir_dwjnbir'.split(
                '_'
            ),
        weekdays: 'asamas_aynas_asinas_akras_akwas_asimwas_asiḍyas'.split('_'),
        weekdaysShort: 'asamas_aynas_asinas_akras_akwas_asimwas_asiḍyas'.split('_'),
        weekdaysMin: 'asamas_aynas_asinas_akras_akwas_asimwas_asiḍyas'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[asdkh g] LT',
            nextDay: '[aska g] LT',
            nextWeek: 'dddd [g] LT',
            lastDay: '[assant g] LT',
            lastWeek: 'dddd [g] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'dadkh s yan %s',
            past: 'yan %s',
            s: 'imik',
            ss: '%d imik',
            m: 'minuḍ',
            mm: '%d minuḍ',
            h: 'saɛa',
            hh: '%d tassaɛin',
            d: 'ass',
            dd: '%d ossan',
            M: 'ayowr',
            MM: '%d iyyirn',
            y: 'asgas',
            yy: '%d isgasn',
        },
        week: {
            dow: 6, // Saturday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('tzm', {
        months: 'ⵉⵏⵏⴰⵢⵔ_ⴱⵕⴰⵢⵕ_ⵎⴰⵕⵚ_ⵉⴱⵔⵉⵔ_ⵎⴰⵢⵢⵓ_ⵢⵓⵏⵢⵓ_ⵢⵓⵍⵢⵓⵣ_ⵖⵓⵛⵜ_ⵛⵓⵜⴰⵏⴱⵉⵔ_ⴽⵟⵓⴱⵕ_ⵏⵓⵡⴰⵏⴱⵉⵔ_ⴷⵓⵊⵏⴱⵉⵔ'.split(
            '_'
        ),
        monthsShort:
            'ⵉⵏⵏⴰⵢⵔ_ⴱⵕⴰⵢⵕ_ⵎⴰⵕⵚ_ⵉⴱⵔⵉⵔ_ⵎⴰⵢⵢⵓ_ⵢⵓⵏⵢⵓ_ⵢⵓⵍⵢⵓⵣ_ⵖⵓⵛⵜ_ⵛⵓⵜⴰⵏⴱⵉⵔ_ⴽⵟⵓⴱⵕ_ⵏⵓⵡⴰⵏⴱⵉⵔ_ⴷⵓⵊⵏⴱⵉⵔ'.split(
                '_'
            ),
        weekdays: 'ⴰⵙⴰⵎⴰⵙ_ⴰⵢⵏⴰⵙ_ⴰⵙⵉⵏⴰⵙ_ⴰⴽⵔⴰⵙ_ⴰⴽⵡⴰⵙ_ⴰⵙⵉⵎⵡⴰⵙ_ⴰⵙⵉⴹⵢⴰⵙ'.split('_'),
        weekdaysShort: 'ⴰⵙⴰⵎⴰⵙ_ⴰⵢⵏⴰⵙ_ⴰⵙⵉⵏⴰⵙ_ⴰⴽⵔⴰⵙ_ⴰⴽⵡⴰⵙ_ⴰⵙⵉⵎⵡⴰⵙ_ⴰⵙⵉⴹⵢⴰⵙ'.split('_'),
        weekdaysMin: 'ⴰⵙⴰⵎⴰⵙ_ⴰⵢⵏⴰⵙ_ⴰⵙⵉⵏⴰⵙ_ⴰⴽⵔⴰⵙ_ⴰⴽⵡⴰⵙ_ⴰⵙⵉⵎⵡⴰⵙ_ⴰⵙⵉⴹⵢⴰⵙ'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[ⴰⵙⴷⵅ ⴴ] LT',
            nextDay: '[ⴰⵙⴽⴰ ⴴ] LT',
            nextWeek: 'dddd [ⴴ] LT',
            lastDay: '[ⴰⵚⴰⵏⵜ ⴴ] LT',
            lastWeek: 'dddd [ⴴ] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'ⴷⴰⴷⵅ ⵙ ⵢⴰⵏ %s',
            past: 'ⵢⴰⵏ %s',
            s: 'ⵉⵎⵉⴽ',
            ss: '%d ⵉⵎⵉⴽ',
            m: 'ⵎⵉⵏⵓⴺ',
            mm: '%d ⵎⵉⵏⵓⴺ',
            h: 'ⵙⴰⵄⴰ',
            hh: '%d ⵜⴰⵙⵙⴰⵄⵉⵏ',
            d: 'ⴰⵙⵙ',
            dd: '%d oⵙⵙⴰⵏ',
            M: 'ⴰⵢoⵓⵔ',
            MM: '%d ⵉⵢⵢⵉⵔⵏ',
            y: 'ⴰⵙⴳⴰⵙ',
            yy: '%d ⵉⵙⴳⴰⵙⵏ',
        },
        week: {
            dow: 6, // Saturday is the first day of the week.
            doy: 12, // The week that contains Jan 12th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('ug-cn', {
        months: 'يانۋار_فېۋرال_مارت_ئاپرېل_ماي_ئىيۇن_ئىيۇل_ئاۋغۇست_سېنتەبىر_ئۆكتەبىر_نويابىر_دېكابىر'.split(
            '_'
        ),
        monthsShort:
            'يانۋار_فېۋرال_مارت_ئاپرېل_ماي_ئىيۇن_ئىيۇل_ئاۋغۇست_سېنتەبىر_ئۆكتەبىر_نويابىر_دېكابىر'.split(
                '_'
            ),
        weekdays: 'يەكشەنبە_دۈشەنبە_سەيشەنبە_چارشەنبە_پەيشەنبە_جۈمە_شەنبە'.split(
            '_'
        ),
        weekdaysShort: 'يە_دۈ_سە_چا_پە_جۈ_شە'.split('_'),
        weekdaysMin: 'يە_دۈ_سە_چا_پە_جۈ_شە'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY-MM-DD',
            LL: 'YYYY-يىلىM-ئاينىڭD-كۈنى',
            LLL: 'YYYY-يىلىM-ئاينىڭD-كۈنى، HH:mm',
            LLLL: 'dddd، YYYY-يىلىM-ئاينىڭD-كۈنى، HH:mm',
        },
        meridiemParse: /يېرىم كېچە|سەھەر|چۈشتىن بۇرۇن|چۈش|چۈشتىن كېيىن|كەچ/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (
                meridiem === 'يېرىم كېچە' ||
                meridiem === 'سەھەر' ||
                meridiem === 'چۈشتىن بۇرۇن'
            ) {
                return hour;
            } else if (meridiem === 'چۈشتىن كېيىن' || meridiem === 'كەچ') {
                return hour + 12;
            } else {
                return hour >= 11 ? hour : hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            var hm = hour * 100 + minute;
            if (hm < 600) {
                return 'يېرىم كېچە';
            } else if (hm < 900) {
                return 'سەھەر';
            } else if (hm < 1130) {
                return 'چۈشتىن بۇرۇن';
            } else if (hm < 1230) {
                return 'چۈش';
            } else if (hm < 1800) {
                return 'چۈشتىن كېيىن';
            } else {
                return 'كەچ';
            }
        },
        calendar: {
            sameDay: '[بۈگۈن سائەت] LT',
            nextDay: '[ئەتە سائەت] LT',
            nextWeek: '[كېلەركى] dddd [سائەت] LT',
            lastDay: '[تۆنۈگۈن] LT',
            lastWeek: '[ئالدىنقى] dddd [سائەت] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s كېيىن',
            past: '%s بۇرۇن',
            s: 'نەچچە سېكونت',
            ss: '%d سېكونت',
            m: 'بىر مىنۇت',
            mm: '%d مىنۇت',
            h: 'بىر سائەت',
            hh: '%d سائەت',
            d: 'بىر كۈن',
            dd: '%d كۈن',
            M: 'بىر ئاي',
            MM: '%d ئاي',
            y: 'بىر يىل',
            yy: '%d يىل',
        },

        dayOfMonthOrdinalParse: /\d{1,2}(-كۈنى|-ئاي|-ھەپتە)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '-كۈنى';
                case 'w':
                case 'W':
                    return number + '-ھەپتە';
                default:
                    return number;
            }
        },
        preparse: function (string) {
            return string.replace(/،/g, ',');
        },
        postformat: function (string) {
            return string.replace(/,/g, '،');
        },
        week: {
            // GB/T 7408-1994《数据元和交换格式·信息交换·日期和时间表示法》与ISO 8601:1988等效
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 1st is the first week of the year.
        },
    });

    //! moment.js locale configuration

    function plural$6(word, num) {
        var forms = word.split('_');
        return num % 10 === 1 && num % 100 !== 11
            ? forms[0]
            : num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20)
            ? forms[1]
            : forms[2];
    }
    function relativeTimeWithPlural$4(number, withoutSuffix, key) {
        var format = {
            ss: withoutSuffix ? 'секунда_секунди_секунд' : 'секунду_секунди_секунд',
            mm: withoutSuffix ? 'хвилина_хвилини_хвилин' : 'хвилину_хвилини_хвилин',
            hh: withoutSuffix ? 'година_години_годин' : 'годину_години_годин',
            dd: 'день_дні_днів',
            MM: 'місяць_місяці_місяців',
            yy: 'рік_роки_років',
        };
        if (key === 'm') {
            return withoutSuffix ? 'хвилина' : 'хвилину';
        } else if (key === 'h') {
            return withoutSuffix ? 'година' : 'годину';
        } else {
            return number + ' ' + plural$6(format[key], +number);
        }
    }
    function weekdaysCaseReplace(m, format) {
        var weekdays = {
                nominative:
                    'неділя_понеділок_вівторок_середа_четвер_п’ятниця_субота'.split(
                        '_'
                    ),
                accusative:
                    'неділю_понеділок_вівторок_середу_четвер_п’ятницю_суботу'.split(
                        '_'
                    ),
                genitive:
                    'неділі_понеділка_вівторка_середи_четверга_п’ятниці_суботи'.split(
                        '_'
                    ),
            },
            nounCase;

        if (m === true) {
            return weekdays['nominative']
                .slice(1, 7)
                .concat(weekdays['nominative'].slice(0, 1));
        }
        if (!m) {
            return weekdays['nominative'];
        }

        nounCase = /(\[[ВвУу]\]) ?dddd/.test(format)
            ? 'accusative'
            : /\[?(?:минулої|наступної)? ?\] ?dddd/.test(format)
            ? 'genitive'
            : 'nominative';
        return weekdays[nounCase][m.day()];
    }
    function processHoursFunction(str) {
        return function () {
            return str + 'о' + (this.hours() === 11 ? 'б' : '') + '] LT';
        };
    }

    hooks.defineLocale('uk', {
        months: {
            format: 'січня_лютого_березня_квітня_травня_червня_липня_серпня_вересня_жовтня_листопада_грудня'.split(
                '_'
            ),
            standalone:
                'січень_лютий_березень_квітень_травень_червень_липень_серпень_вересень_жовтень_листопад_грудень'.split(
                    '_'
                ),
        },
        monthsShort: 'січ_лют_бер_квіт_трав_черв_лип_серп_вер_жовт_лист_груд'.split(
            '_'
        ),
        weekdays: weekdaysCaseReplace,
        weekdaysShort: 'нд_пн_вт_ср_чт_пт_сб'.split('_'),
        weekdaysMin: 'нд_пн_вт_ср_чт_пт_сб'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD.MM.YYYY',
            LL: 'D MMMM YYYY р.',
            LLL: 'D MMMM YYYY р., HH:mm',
            LLLL: 'dddd, D MMMM YYYY р., HH:mm',
        },
        calendar: {
            sameDay: processHoursFunction('[Сьогодні '),
            nextDay: processHoursFunction('[Завтра '),
            lastDay: processHoursFunction('[Вчора '),
            nextWeek: processHoursFunction('[У] dddd ['),
            lastWeek: function () {
                switch (this.day()) {
                    case 0:
                    case 3:
                    case 5:
                    case 6:
                        return processHoursFunction('[Минулої] dddd [').call(this);
                    case 1:
                    case 2:
                    case 4:
                        return processHoursFunction('[Минулого] dddd [').call(this);
                }
            },
            sameElse: 'L',
        },
        relativeTime: {
            future: 'за %s',
            past: '%s тому',
            s: 'декілька секунд',
            ss: relativeTimeWithPlural$4,
            m: relativeTimeWithPlural$4,
            mm: relativeTimeWithPlural$4,
            h: 'годину',
            hh: relativeTimeWithPlural$4,
            d: 'день',
            dd: relativeTimeWithPlural$4,
            M: 'місяць',
            MM: relativeTimeWithPlural$4,
            y: 'рік',
            yy: relativeTimeWithPlural$4,
        },
        // M. E.: those two are virtually unused but a user might want to implement them for his/her website for some reason
        meridiemParse: /ночі|ранку|дня|вечора/,
        isPM: function (input) {
            return /^(дня|вечора)$/.test(input);
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 4) {
                return 'ночі';
            } else if (hour < 12) {
                return 'ранку';
            } else if (hour < 17) {
                return 'дня';
            } else {
                return 'вечора';
            }
        },
        dayOfMonthOrdinalParse: /\d{1,2}-(й|го)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'M':
                case 'd':
                case 'DDD':
                case 'w':
                case 'W':
                    return number + '-й';
                case 'D':
                    return number + '-го';
                default:
                    return number;
            }
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    var months$b = [
            'جنوری',
            'فروری',
            'مارچ',
            'اپریل',
            'مئی',
            'جون',
            'جولائی',
            'اگست',
            'ستمبر',
            'اکتوبر',
            'نومبر',
            'دسمبر',
        ],
        days$2 = ['اتوار', 'پیر', 'منگل', 'بدھ', 'جمعرات', 'جمعہ', 'ہفتہ'];

    hooks.defineLocale('ur', {
        months: months$b,
        monthsShort: months$b,
        weekdays: days$2,
        weekdaysShort: days$2,
        weekdaysMin: days$2,
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd، D MMMM YYYY HH:mm',
        },
        meridiemParse: /صبح|شام/,
        isPM: function (input) {
            return 'شام' === input;
        },
        meridiem: function (hour, minute, isLower) {
            if (hour < 12) {
                return 'صبح';
            }
            return 'شام';
        },
        calendar: {
            sameDay: '[آج بوقت] LT',
            nextDay: '[کل بوقت] LT',
            nextWeek: 'dddd [بوقت] LT',
            lastDay: '[گذشتہ روز بوقت] LT',
            lastWeek: '[گذشتہ] dddd [بوقت] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s بعد',
            past: '%s قبل',
            s: 'چند سیکنڈ',
            ss: '%d سیکنڈ',
            m: 'ایک منٹ',
            mm: '%d منٹ',
            h: 'ایک گھنٹہ',
            hh: '%d گھنٹے',
            d: 'ایک دن',
            dd: '%d دن',
            M: 'ایک ماہ',
            MM: '%d ماہ',
            y: 'ایک سال',
            yy: '%d سال',
        },
        preparse: function (string) {
            return string.replace(/،/g, ',');
        },
        postformat: function (string) {
            return string.replace(/,/g, '،');
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('uz-latn', {
        months: 'Yanvar_Fevral_Mart_Aprel_May_Iyun_Iyul_Avgust_Sentabr_Oktabr_Noyabr_Dekabr'.split(
            '_'
        ),
        monthsShort: 'Yan_Fev_Mar_Apr_May_Iyun_Iyul_Avg_Sen_Okt_Noy_Dek'.split('_'),
        weekdays:
            'Yakshanba_Dushanba_Seshanba_Chorshanba_Payshanba_Juma_Shanba'.split(
                '_'
            ),
        weekdaysShort: 'Yak_Dush_Sesh_Chor_Pay_Jum_Shan'.split('_'),
        weekdaysMin: 'Ya_Du_Se_Cho_Pa_Ju_Sha'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'D MMMM YYYY, dddd HH:mm',
        },
        calendar: {
            sameDay: '[Bugun soat] LT [da]',
            nextDay: '[Ertaga] LT [da]',
            nextWeek: 'dddd [kuni soat] LT [da]',
            lastDay: '[Kecha soat] LT [da]',
            lastWeek: "[O'tgan] dddd [kuni soat] LT [da]",
            sameElse: 'L',
        },
        relativeTime: {
            future: 'Yaqin %s ichida',
            past: 'Bir necha %s oldin',
            s: 'soniya',
            ss: '%d soniya',
            m: 'bir daqiqa',
            mm: '%d daqiqa',
            h: 'bir soat',
            hh: '%d soat',
            d: 'bir kun',
            dd: '%d kun',
            M: 'bir oy',
            MM: '%d oy',
            y: 'bir yil',
            yy: '%d yil',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 7th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('uz', {
        months: 'январ_феврал_март_апрел_май_июн_июл_август_сентябр_октябр_ноябр_декабр'.split(
            '_'
        ),
        monthsShort: 'янв_фев_мар_апр_май_июн_июл_авг_сен_окт_ноя_дек'.split('_'),
        weekdays: 'Якшанба_Душанба_Сешанба_Чоршанба_Пайшанба_Жума_Шанба'.split('_'),
        weekdaysShort: 'Якш_Душ_Сеш_Чор_Пай_Жум_Шан'.split('_'),
        weekdaysMin: 'Як_Ду_Се_Чо_Па_Жу_Ша'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'D MMMM YYYY, dddd HH:mm',
        },
        calendar: {
            sameDay: '[Бугун соат] LT [да]',
            nextDay: '[Эртага] LT [да]',
            nextWeek: 'dddd [куни соат] LT [да]',
            lastDay: '[Кеча соат] LT [да]',
            lastWeek: '[Утган] dddd [куни соат] LT [да]',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'Якин %s ичида',
            past: 'Бир неча %s олдин',
            s: 'фурсат',
            ss: '%d фурсат',
            m: 'бир дакика',
            mm: '%d дакика',
            h: 'бир соат',
            hh: '%d соат',
            d: 'бир кун',
            dd: '%d кун',
            M: 'бир ой',
            MM: '%d ой',
            y: 'бир йил',
            yy: '%d йил',
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('vi', {
        months: 'tháng 1_tháng 2_tháng 3_tháng 4_tháng 5_tháng 6_tháng 7_tháng 8_tháng 9_tháng 10_tháng 11_tháng 12'.split(
            '_'
        ),
        monthsShort:
            'Thg 01_Thg 02_Thg 03_Thg 04_Thg 05_Thg 06_Thg 07_Thg 08_Thg 09_Thg 10_Thg 11_Thg 12'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays: 'chủ nhật_thứ hai_thứ ba_thứ tư_thứ năm_thứ sáu_thứ bảy'.split(
            '_'
        ),
        weekdaysShort: 'CN_T2_T3_T4_T5_T6_T7'.split('_'),
        weekdaysMin: 'CN_T2_T3_T4_T5_T6_T7'.split('_'),
        weekdaysParseExact: true,
        meridiemParse: /sa|ch/i,
        isPM: function (input) {
            return /^ch$/i.test(input);
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 12) {
                return isLower ? 'sa' : 'SA';
            } else {
                return isLower ? 'ch' : 'CH';
            }
        },
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM [năm] YYYY',
            LLL: 'D MMMM [năm] YYYY HH:mm',
            LLLL: 'dddd, D MMMM [năm] YYYY HH:mm',
            l: 'DD/M/YYYY',
            ll: 'D MMM YYYY',
            lll: 'D MMM YYYY HH:mm',
            llll: 'ddd, D MMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[Hôm nay lúc] LT',
            nextDay: '[Ngày mai lúc] LT',
            nextWeek: 'dddd [tuần tới lúc] LT',
            lastDay: '[Hôm qua lúc] LT',
            lastWeek: 'dddd [tuần trước lúc] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: '%s tới',
            past: '%s trước',
            s: 'vài giây',
            ss: '%d giây',
            m: 'một phút',
            mm: '%d phút',
            h: 'một giờ',
            hh: '%d giờ',
            d: 'một ngày',
            dd: '%d ngày',
            w: 'một tuần',
            ww: '%d tuần',
            M: 'một tháng',
            MM: '%d tháng',
            y: 'một năm',
            yy: '%d năm',
        },
        dayOfMonthOrdinalParse: /\d{1,2}/,
        ordinal: function (number) {
            return number;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('x-pseudo', {
        months: 'J~áñúá~rý_F~ébrú~árý_~Márc~h_Áp~ríl_~Máý_~Júñé~_Júl~ý_Áú~gúst~_Sép~témb~ér_Ó~ctób~ér_Ñ~óvém~bér_~Décé~mbér'.split(
            '_'
        ),
        monthsShort:
            'J~áñ_~Féb_~Már_~Ápr_~Máý_~Júñ_~Júl_~Áúg_~Sép_~Óct_~Ñóv_~Déc'.split(
                '_'
            ),
        monthsParseExact: true,
        weekdays:
            'S~úñdá~ý_Mó~ñdáý~_Túé~sdáý~_Wéd~ñésd~áý_T~húrs~dáý_~Fríd~áý_S~átúr~dáý'.split(
                '_'
            ),
        weekdaysShort: 'S~úñ_~Móñ_~Túé_~Wéd_~Thú_~Frí_~Sát'.split('_'),
        weekdaysMin: 'S~ú_Mó~_Tú_~Wé_T~h_Fr~_Sá'.split('_'),
        weekdaysParseExact: true,
        longDateFormat: {
            LT: 'HH:mm',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
        },
        calendar: {
            sameDay: '[T~ódá~ý át] LT',
            nextDay: '[T~ómó~rró~w át] LT',
            nextWeek: 'dddd [át] LT',
            lastDay: '[Ý~ést~érdá~ý át] LT',
            lastWeek: '[L~ást] dddd [át] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'í~ñ %s',
            past: '%s á~gó',
            s: 'á ~féw ~sécó~ñds',
            ss: '%d s~écóñ~ds',
            m: 'á ~míñ~úté',
            mm: '%d m~íñú~tés',
            h: 'á~ñ hó~úr',
            hh: '%d h~óúrs',
            d: 'á ~dáý',
            dd: '%d d~áýs',
            M: 'á ~móñ~th',
            MM: '%d m~óñt~hs',
            y: 'á ~ýéár',
            yy: '%d ý~éárs',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(th|st|nd|rd)/,
        ordinal: function (number) {
            var b = number % 10,
                output =
                    ~~((number % 100) / 10) === 1
                        ? 'th'
                        : b === 1
                        ? 'st'
                        : b === 2
                        ? 'nd'
                        : b === 3
                        ? 'rd'
                        : 'th';
            return number + output;
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('yo', {
        months: 'Sẹ́rẹ́_Èrèlè_Ẹrẹ̀nà_Ìgbé_Èbibi_Òkùdu_Agẹmo_Ògún_Owewe_Ọ̀wàrà_Bélú_Ọ̀pẹ̀̀'.split(
            '_'
        ),
        monthsShort: 'Sẹ́r_Èrl_Ẹrn_Ìgb_Èbi_Òkù_Agẹ_Ògú_Owe_Ọ̀wà_Bél_Ọ̀pẹ̀̀'.split('_'),
        weekdays: 'Àìkú_Ajé_Ìsẹ́gun_Ọjọ́rú_Ọjọ́bọ_Ẹtì_Àbámẹ́ta'.split('_'),
        weekdaysShort: 'Àìk_Ajé_Ìsẹ́_Ọjr_Ọjb_Ẹtì_Àbá'.split('_'),
        weekdaysMin: 'Àì_Aj_Ìs_Ọr_Ọb_Ẹt_Àb'.split('_'),
        longDateFormat: {
            LT: 'h:mm A',
            LTS: 'h:mm:ss A',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY h:mm A',
            LLLL: 'dddd, D MMMM YYYY h:mm A',
        },
        calendar: {
            sameDay: '[Ònì ni] LT',
            nextDay: '[Ọ̀la ni] LT',
            nextWeek: "dddd [Ọsẹ̀ tón'bọ] [ni] LT",
            lastDay: '[Àna ni] LT',
            lastWeek: 'dddd [Ọsẹ̀ tólọ́] [ni] LT',
            sameElse: 'L',
        },
        relativeTime: {
            future: 'ní %s',
            past: '%s kọjá',
            s: 'ìsẹjú aayá die',
            ss: 'aayá %d',
            m: 'ìsẹjú kan',
            mm: 'ìsẹjú %d',
            h: 'wákati kan',
            hh: 'wákati %d',
            d: 'ọjọ́ kan',
            dd: 'ọjọ́ %d',
            M: 'osù kan',
            MM: 'osù %d',
            y: 'ọdún kan',
            yy: 'ọdún %d',
        },
        dayOfMonthOrdinalParse: /ọjọ́\s\d{1,2}/,
        ordinal: 'ọjọ́ %d',
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('zh-cn', {
        months: '一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月'.split(
            '_'
        ),
        monthsShort: '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split(
            '_'
        ),
        weekdays: '星期日_星期一_星期二_星期三_星期四_星期五_星期六'.split('_'),
        weekdaysShort: '周日_周一_周二_周三_周四_周五_周六'.split('_'),
        weekdaysMin: '日_一_二_三_四_五_六'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY/MM/DD',
            LL: 'YYYY年M月D日',
            LLL: 'YYYY年M月D日Ah点mm分',
            LLLL: 'YYYY年M月D日ddddAh点mm分',
            l: 'YYYY/M/D',
            ll: 'YYYY年M月D日',
            lll: 'YYYY年M月D日 HH:mm',
            llll: 'YYYY年M月D日dddd HH:mm',
        },
        meridiemParse: /凌晨|早上|上午|中午|下午|晚上/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === '凌晨' || meridiem === '早上' || meridiem === '上午') {
                return hour;
            } else if (meridiem === '下午' || meridiem === '晚上') {
                return hour + 12;
            } else {
                // '中午'
                return hour >= 11 ? hour : hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            var hm = hour * 100 + minute;
            if (hm < 600) {
                return '凌晨';
            } else if (hm < 900) {
                return '早上';
            } else if (hm < 1130) {
                return '上午';
            } else if (hm < 1230) {
                return '中午';
            } else if (hm < 1800) {
                return '下午';
            } else {
                return '晚上';
            }
        },
        calendar: {
            sameDay: '[今天]LT',
            nextDay: '[明天]LT',
            nextWeek: function (now) {
                if (now.week() !== this.week()) {
                    return '[下]dddLT';
                } else {
                    return '[本]dddLT';
                }
            },
            lastDay: '[昨天]LT',
            lastWeek: function (now) {
                if (this.week() !== now.week()) {
                    return '[上]dddLT';
                } else {
                    return '[本]dddLT';
                }
            },
            sameElse: 'L',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(日|月|周)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '日';
                case 'M':
                    return number + '月';
                case 'w':
                case 'W':
                    return number + '周';
                default:
                    return number;
            }
        },
        relativeTime: {
            future: '%s后',
            past: '%s前',
            s: '几秒',
            ss: '%d 秒',
            m: '1 分钟',
            mm: '%d 分钟',
            h: '1 小时',
            hh: '%d 小时',
            d: '1 天',
            dd: '%d 天',
            w: '1 周',
            ww: '%d 周',
            M: '1 个月',
            MM: '%d 个月',
            y: '1 年',
            yy: '%d 年',
        },
        week: {
            // GB/T 7408-1994《数据元和交换格式·信息交换·日期和时间表示法》与ISO 8601:1988等效
            dow: 1, // Monday is the first day of the week.
            doy: 4, // The week that contains Jan 4th is the first week of the year.
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('zh-hk', {
        months: '一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月'.split(
            '_'
        ),
        monthsShort: '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split(
            '_'
        ),
        weekdays: '星期日_星期一_星期二_星期三_星期四_星期五_星期六'.split('_'),
        weekdaysShort: '週日_週一_週二_週三_週四_週五_週六'.split('_'),
        weekdaysMin: '日_一_二_三_四_五_六'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY/MM/DD',
            LL: 'YYYY年M月D日',
            LLL: 'YYYY年M月D日 HH:mm',
            LLLL: 'YYYY年M月D日dddd HH:mm',
            l: 'YYYY/M/D',
            ll: 'YYYY年M月D日',
            lll: 'YYYY年M月D日 HH:mm',
            llll: 'YYYY年M月D日dddd HH:mm',
        },
        meridiemParse: /凌晨|早上|上午|中午|下午|晚上/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === '凌晨' || meridiem === '早上' || meridiem === '上午') {
                return hour;
            } else if (meridiem === '中午') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === '下午' || meridiem === '晚上') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            var hm = hour * 100 + minute;
            if (hm < 600) {
                return '凌晨';
            } else if (hm < 900) {
                return '早上';
            } else if (hm < 1200) {
                return '上午';
            } else if (hm === 1200) {
                return '中午';
            } else if (hm < 1800) {
                return '下午';
            } else {
                return '晚上';
            }
        },
        calendar: {
            sameDay: '[今天]LT',
            nextDay: '[明天]LT',
            nextWeek: '[下]ddddLT',
            lastDay: '[昨天]LT',
            lastWeek: '[上]ddddLT',
            sameElse: 'L',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(日|月|週)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '日';
                case 'M':
                    return number + '月';
                case 'w':
                case 'W':
                    return number + '週';
                default:
                    return number;
            }
        },
        relativeTime: {
            future: '%s後',
            past: '%s前',
            s: '幾秒',
            ss: '%d 秒',
            m: '1 分鐘',
            mm: '%d 分鐘',
            h: '1 小時',
            hh: '%d 小時',
            d: '1 天',
            dd: '%d 天',
            M: '1 個月',
            MM: '%d 個月',
            y: '1 年',
            yy: '%d 年',
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('zh-mo', {
        months: '一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月'.split(
            '_'
        ),
        monthsShort: '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split(
            '_'
        ),
        weekdays: '星期日_星期一_星期二_星期三_星期四_星期五_星期六'.split('_'),
        weekdaysShort: '週日_週一_週二_週三_週四_週五_週六'.split('_'),
        weekdaysMin: '日_一_二_三_四_五_六'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'YYYY年M月D日',
            LLL: 'YYYY年M月D日 HH:mm',
            LLLL: 'YYYY年M月D日dddd HH:mm',
            l: 'D/M/YYYY',
            ll: 'YYYY年M月D日',
            lll: 'YYYY年M月D日 HH:mm',
            llll: 'YYYY年M月D日dddd HH:mm',
        },
        meridiemParse: /凌晨|早上|上午|中午|下午|晚上/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === '凌晨' || meridiem === '早上' || meridiem === '上午') {
                return hour;
            } else if (meridiem === '中午') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === '下午' || meridiem === '晚上') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            var hm = hour * 100 + minute;
            if (hm < 600) {
                return '凌晨';
            } else if (hm < 900) {
                return '早上';
            } else if (hm < 1130) {
                return '上午';
            } else if (hm < 1230) {
                return '中午';
            } else if (hm < 1800) {
                return '下午';
            } else {
                return '晚上';
            }
        },
        calendar: {
            sameDay: '[今天] LT',
            nextDay: '[明天] LT',
            nextWeek: '[下]dddd LT',
            lastDay: '[昨天] LT',
            lastWeek: '[上]dddd LT',
            sameElse: 'L',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(日|月|週)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '日';
                case 'M':
                    return number + '月';
                case 'w':
                case 'W':
                    return number + '週';
                default:
                    return number;
            }
        },
        relativeTime: {
            future: '%s內',
            past: '%s前',
            s: '幾秒',
            ss: '%d 秒',
            m: '1 分鐘',
            mm: '%d 分鐘',
            h: '1 小時',
            hh: '%d 小時',
            d: '1 天',
            dd: '%d 天',
            M: '1 個月',
            MM: '%d 個月',
            y: '1 年',
            yy: '%d 年',
        },
    });

    //! moment.js locale configuration

    hooks.defineLocale('zh-tw', {
        months: '一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月'.split(
            '_'
        ),
        monthsShort: '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split(
            '_'
        ),
        weekdays: '星期日_星期一_星期二_星期三_星期四_星期五_星期六'.split('_'),
        weekdaysShort: '週日_週一_週二_週三_週四_週五_週六'.split('_'),
        weekdaysMin: '日_一_二_三_四_五_六'.split('_'),
        longDateFormat: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'YYYY/MM/DD',
            LL: 'YYYY年M月D日',
            LLL: 'YYYY年M月D日 HH:mm',
            LLLL: 'YYYY年M月D日dddd HH:mm',
            l: 'YYYY/M/D',
            ll: 'YYYY年M月D日',
            lll: 'YYYY年M月D日 HH:mm',
            llll: 'YYYY年M月D日dddd HH:mm',
        },
        meridiemParse: /凌晨|早上|上午|中午|下午|晚上/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === '凌晨' || meridiem === '早上' || meridiem === '上午') {
                return hour;
            } else if (meridiem === '中午') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === '下午' || meridiem === '晚上') {
                return hour + 12;
            }
        },
        meridiem: function (hour, minute, isLower) {
            var hm = hour * 100 + minute;
            if (hm < 600) {
                return '凌晨';
            } else if (hm < 900) {
                return '早上';
            } else if (hm < 1130) {
                return '上午';
            } else if (hm < 1230) {
                return '中午';
            } else if (hm < 1800) {
                return '下午';
            } else {
                return '晚上';
            }
        },
        calendar: {
            sameDay: '[今天] LT',
            nextDay: '[明天] LT',
            nextWeek: '[下]dddd LT',
            lastDay: '[昨天] LT',
            lastWeek: '[上]dddd LT',
            sameElse: 'L',
        },
        dayOfMonthOrdinalParse: /\d{1,2}(日|月|週)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '日';
                case 'M':
                    return number + '月';
                case 'w':
                case 'W':
                    return number + '週';
                default:
                    return number;
            }
        },
        relativeTime: {
            future: '%s後',
            past: '%s前',
            s: '幾秒',
            ss: '%d 秒',
            m: '1 分鐘',
            mm: '%d 分鐘',
            h: '1 小時',
            hh: '%d 小時',
            d: '1 天',
            dd: '%d 天',
            M: '1 個月',
            MM: '%d 個月',
            y: '1 年',
            yy: '%d 年',
        },
    });

    hooks.locale('en');

    return hooks;

})));
Ask AI to edit or generate...
