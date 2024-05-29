from driver import inject_driver
import navigation.wait as wait
import navigation.source as src
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.common.by import By


class PageElementFactory:
    def create_element(self, source):
        elements = {
            src.ElementXPath.SORT_MENU: (
                Anchor, 
                [src.ElementXPath.SORT_MENU, wait.LocatableSourceWait()]
            ),
            src.PopupXPaths.COOKIE_AGREEMENT: (
                Popup, 
                [src.PopupXPaths.COOKIE_AGREEMENT, wait.LocatableSourceWait()]
            ),
            src.PopupXPaths.PERSONAL_DATA: (
                Popup, 
                [src.PopupXPaths.PERSONAL_DATA, wait.LocatableSourceWait()]
            )
        }
        cls_params_pair = elements[source] 
        cls = cls_params_pair[0]
        params = cls_params_pair[1]
        return cls(*params)

class PageElement:
    def __init__(self, source, wait):
        self._wait = wait
        self._source = source

    def until_on_page(self):
        if self._wait:
            self._wait.until(self._source)

    #add logging in except handler
    @inject_driver()
    def locate(self, driver = None):
        try:    
            self._element = driver.find_element(By.XPATH, self._source)
        except NoSuchElementException as e:
            print(e)

class Popup(PageElement):
    @inject_driver()
    def close(self, driver = None):
        self.until_on_page()
        self.locate()
        if self._element:
            driver.execute_script('arguments[0].click()', self._element)

#Don't need it yet but who knows...
class Button(PageElement):
    def click(self):
        self.until_on_page()
        self.locate()
        if self._element:
            self._element.click()
        
class Anchor(PageElement):
    def wait(self):
        self.until_on_page()
