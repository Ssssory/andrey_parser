from enum import StrEnum


class Urls(StrEnum):
    RENTS_LIST = 'https://www.4zida.rs/izdavanje-stanova?strana={0}'

class ElementXPath(StrEnum):
    SORT_MENU = '//button[span[text()=\'Sortiraj\']]'

class PopupXPaths(StrEnum):
    COOKIE_AGREEMENT = '//form/input/following-sibling::button'
    PERSONAL_DATA = '//button[@aria-label=\'Сагласан/на сам\']'


