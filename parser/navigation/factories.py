import navigation.source as src 
from navigation import element
#import navigation.element as element
from navigation.protocols import ElementFactory
from navigation import wait
from navigation.page import AdsListPage, Page


#hm... refactor m.b.?
class PageElementFactory:
    def create_element(self, source: src.ElementXPath):
        elements = {
            src.ElementXPath.SORT_MENU: (
                element.Anchor,
                [src.ElementXPath.SORT_MENU, wait.LocatableSourceWait()]
            ),
            src.ElementXPath.COOKIE_AGREEMENT: (
                element.Popup,
                [src.ElementXPath.COOKIE_AGREEMENT, wait.LocatableSourceWait()]
            ),
            src.ElementXPath.PERSONAL_DATA: (
                element.Popup,
                [src.ElementXPath.PERSONAL_DATA, wait.LocatableSourceWait()]
            ),
            src.ElementXPath.PRINT_BUTTON: (
                element.Anchor,
                [src.ElementXPath.PRINT_BUTTON, wait.LocatableSourceWait()]
            )
        }
        cls_params_pair = elements[source]
        cls = cls_params_pair[0]
        params = cls_params_pair[1]
        return cls(*params)

class AdsListPageFactory:
    def __init__(self, factory: ElementFactory):
        self._factory = factory
        self._page_object = None

    def create_page(self, page_number=1):
        anchor = self._factory.create_element(src.ElementXPath.SORT_MENU)
        self._page_object = AdsListPage(anchor, page_number)
        self._register_page_observers()
        return self._page_object

    def _register_page_observers(self):
        data_popup = self._factory.create_element(src.ElementXPath.PERSONAL_DATA)
        cookie_popup = self._factory.create_element(src.ElementXPath.COOKIE_AGREEMENT)
        self._page_object.register_obsever(data_popup)
        self._page_object.register_obsever(cookie_popup)

class AdPageFactory:
    def __init__(self, factory: ElementFactory):
        self._factory = factory

    def create_page(self, source):
        anchor = self._factory.create_element(src.ElementXPath.PRINT_BUTTON)
        return Page(anchor, source)
