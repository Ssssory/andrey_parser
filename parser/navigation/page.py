from driver import inject_driver
import navigation.source as src


class Page():
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
    _cookie_popup_src = src.PopupXPaths.COOKIE_AGREEMENT
    _private_popup_src = src.PopupXPaths.PERSONAL_DATA

    def __init__(self, factory):
        super().__init__(self._src)
        self._anchor = factory.create_element(self._anchor_src)
        self._cookie_popup = factory.create_element(self._cookie_popup_src)
        self._private_popup = factory.create_element(self._private_popup_src)

    def load(self):
        super().load()
        self._anchor.wait()
        self._cookie_popup.close()
        self._private_popup.close()
