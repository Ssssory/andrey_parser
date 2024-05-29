from enum import StrEnum


class Urls(StrEnum):
    RENTS_LIST = 'https://www.4zida.rs/izdavanje-stanova'

class ElementXPath(StrEnum):
    RENT_FILTER = '//form//button[text()=\'Izdavanje\']'
    SEARCH_BUTTON = '//form//button[@type=\'submit\']'
    SORT_MENU = '//button[span[text()=\'Sortiraj\']]'

class PopupXPaths(StrEnum):
    COOKIE_AGREEMENT = '//form/input/following-sibling::button'
    PERSONAL_DATA = '//button[@aria-label=\'Сагласан/на сам\']'


