"""Parsable pages of a target"""
import navigation.source as src
from navigation.element import PageElementFactory
from driver import inject_driver


class Page:
    """Base class for parsable pages"""
    def __init__(self, source):
        self._source = source

    def load(self, driver = None):
        """Load page using source"""
        driver.get(self._source)

    @inject_driver()
    def get_content(self, driver = None):
        """Get page html"""
        return driver.page_source

class RentsListPage(Page):
    """Parsable page with list of ads"""
    _src = src.Urls.RENTS_LIST
    _anchor_src = src.ElementXPath.SORT_MENU

    def __init__(self, factory: PageElementFactory, page_number = 1):
        self._src = self._src.format(page_number)
        super().__init__(self._src)
        self._observers = []
        self._anchor = factory.create_element(self._anchor_src)

    def register_obsever(self, observer):
        """Register obsevers to notify when page is loaded"""
        self._observers.append(observer)

    @inject_driver()
    def load(self, driver = None):
        super().load(driver)
        self._anchor.wait()
        self._notify()

    def _notify(self):
        for observer in self._observers:
            observer.update()


class RentsListPageFactory:
    def __init__(self, factory: PageElementFactory, paths: src.PopupXPaths, page: RentsListPage):
        self._factory = factory
        self._paths = paths
        self._page = page
        self._page_object = None

    def create_page(self, page_number=1):
        self._page_object = self._page(self._factory, page_number)
        self._register_page_observers()
        return self._page_object

    def _register_page_observers(self):
        data_popup = self._factory.create_element(self._paths.PERSONAL_DATA)
        cookie_popup = self._factory.create_element(self._paths.COOKIE_AGREEMENT)
        self._page_object.register_obsever(data_popup)
        self._page_object.register_obsever(cookie_popup)

class PageIterator:
    """Iterator for pages with page numbers"""
    def __init__(self, last_number: int, factory: RentsListPageFactory):
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
