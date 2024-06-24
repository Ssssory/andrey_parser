"""XPath and urls sources""" 
from enum import StrEnum


class Urls(StrEnum):
    """Url sources"""
    DOMAIN = 'https://www.4zida.rs'
    RENTS_LIST = 'https://www.4zida.rs/izdavanje-stanova?strana={0}'

class ElementXPath(StrEnum):
    """XPaths to elements on pages"""
    SORT_MENU = '//button[span[text()=\'Sortiraj\']]'

class PopupXPaths(StrEnum):
    """XPaths to popus on pages"""
    COOKIE_AGREEMENT = '//form/input/following-sibling::button'
    PERSONAL_DATA = '//button[@aria-label=\'Сагласан/на сам\']'
