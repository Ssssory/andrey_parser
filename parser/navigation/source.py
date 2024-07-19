"""XPath and urls sources""" 
from enum import StrEnum


class Urls(StrEnum):
    """Url sources"""
    DOMAIN = 'https://www.4zida.rs'
    RENTS_LIST = 'https://www.4zida.rs/izdavanje-stanova?strana={0}'

class ElementXPath(StrEnum):
    """XPaths to elements on pages"""
    SORT_MENU = '//button[span[text()=\'Sortiraj\']]'
    COOKIE_AGREEMENT = '//form/input/following-sibling::button'
    PERSONAL_DATA = '//button[@aria-label=\'Сагласан/на сам\']'
    PRINT_BUTTON = '//span[text()=\'Štampaj\']'

class Tags(StrEnum):
    """Tags for BeautifullSoup search"""
    HEADER = 'h1'
    DETAILS = 'section'
    DESCRIPTION = 'p'
    DESCRIPTION_HEADER = 'section'
    SEARCH_START = 'img'
    AD_IMAGE = 'img'
    AD_LINK = 'a'

class Css(StrEnum):
    """Css classes for BeautifullSoup search"""
    DETAILS = 'flex flex-col gap-1'
    DESCRIPTION_HEADER = 'mt-4 flex flex-col gap-1'
    DESCRIPTION = 'mb-4'
    SEARCH_START = 'object-cover'
