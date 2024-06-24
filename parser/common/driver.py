from selenium import webdriver
from selenium.webdriver.chrome.options import Options


class Driver():
    _driver = None
    _debug_is_on = None

    def __new__(cls):
        if not hasattr(cls, 'instance'):
            cls.instance = super(Driver, cls).__new__(cls)

        return cls.instance

    def update(self, debug_is_on):
        self._driver = None
        self._debug_is_on = debug_is_on

    def get(self):
        if self._driver:
            return self._driver

        if self._debug_is_on:
            self._driver = webdriver.Chrome()
        else:
            options = Options()
            options.add_argument('--headless=new')
            self._driver = webdriver.Chrome(options)

        return self._driver


#here the interface should be used not the implementation
def inject_driver(driver = Driver):
    def inner(func):
        def wrapper(*args, **kwargs):
            d = driver().get()
            return func(*args, **kwargs, driver=d)

        return wrapper

    return inner
