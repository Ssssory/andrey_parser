import navigation.source as src
from navigation.element import PageElementFactory
from driver import inject_driver
from enum import Enum, auto


class PagesEnum(Enum):
    RENTS_LIST = auto()

class Page:
    def __init__(self, source):
        self._source = source

    @inject_driver()
    def load(self, driver = None):
        driver.get(self._source)

    @inject_driver()
    def get_content(self, driver = None):
        return driver.page_source

class RentsListPage(Page):
    _src = src.Urls.RENTS_LIST
    _anchor_src = src.ElementXPath.SORT_MENU
    _observers = []

    def __init__(self, factory, page_number = 1):
        self._src = self._src.format(page_number)
        super().__init__(self._src)
        self._anchor = factory.create_element(self._anchor_src)

    def load(self):
        super().load()
        self._anchor.wait()
        self._notify()

    def register_obsever(self, observer):
        self._observers.append(observer)

    def _notify(self):
        for observer in self._observers:
            observer.update()

class PageIterator:
    def __init__(self, last_number, page_factory, page):
        self._current_number = 1
        self._last_number = last_number
        self._factory = page_factory
        self._page = page

    def __iter__(self):
        return self

    def __next__(self):
        if self._current_number <= self._last_number:
            page = self._factory.create_page(
                    self._page,
                    page_number = self._current_number)
            self._current_number += 1
        else:
            raise StopIteration

        return page

class PageFactory:
    def create_page(self, page, **kwargs):
        pages = {
                PagesEnum.RENTS_LIST: (RentsListPage, (PageElementFactory(),))
        }
        cls_prms_pair = pages[page] 
        cls = cls_prms_pair[0]
        params = cls_prms_pair[1]

        return cls(*params, **kwargs)
