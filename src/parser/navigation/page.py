from driver import inject_driver
from selenium.webdriver.common.by import By
from selenium.common.exceptions import NoSuchElementException


class Page:
    def __init__(self):
        self._source = ''

    @inject_driver()
    def load(self, source, driver = None):
        driver.get(source)
        self._source = driver.current_url 

class PageElement:
    def __init__(self, source, wait = None):
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
            self._element = None

class Popup(PageElement):
    @inject_driver()
    def close(self, driver = None):
        self.until_on_page()
        self.locate()
        if self._element:
            driver.execute_script('arguments[0].click()', self._element)

class Button(PageElement):
    def click(self):
        self.until_on_page()
        self.locate()
        if self._element:
            self._element.click()
        
class Anchor(PageElement):
    def wait(self):
        self.until_on_page()
