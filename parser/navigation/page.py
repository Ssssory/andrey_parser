"""Parsable pages of a target"""
from navigation.protocols import (
        PageFactory,
        AnchorElement)
import navigation.source as src
from common.driver import inject_driver


class Page:
    """Base class for parsable pages"""
    def __init__(self, anchor: AnchorElement, source):
        self._source = source
        self._anchor = anchor

    @inject_driver()
    def load(self, driver = None):
        """Load page using source"""
        driver.get(self._source)
        self._anchor.wait()

    @inject_driver()
    def get_content(self, driver = None):
        """Get page html"""
        return driver.page_source


class AdsListPage(Page):
    """Parsable page with list of ads"""
    _src = src.Urls.RENTS_LIST

    def __init__(self, anchor: AnchorElement, page_number = 1):
        self._src = self._src.format(page_number)
        super().__init__(anchor, self._src)
        self._observers = []

    def register_obsever(self, observer):
        """Register obsevers to notify when page is loaded"""
        self._observers.append(observer)

    def load(self, driver = None):
        super().load()
        self._notify()

    def _notify(self):
        for observer in self._observers:
            observer.update()

class PageIterator:
    """Iterator for pages with page numbers"""
    def __init__(self, last_number: int, factory: PageFactory):
        self._current_number = 1
        self._last_number = last_number
        self._factory = factory

    def __iter__(self):
        return self

    def __next__(self):
        if self._current_number <= self._last_number:
            page = self._factory.create_page(
                    page_number = self._current_number)
            self._current_number += 1
        else:
            raise StopIteration

        return page
