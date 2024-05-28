from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from functools import wraps


def set_debug(on):
    mode = DebugMode()
    mode.register_observer(Driver())
    if on:
        mode.set_on()
    else:
        mode.set_off()


class DebugMode():
    _on = False
    _observers = []

    def __new__(cls):
        if not hasattr(cls, 'instance'):
            cls.instance = super(DebugMode, cls).__new__(cls)

        return cls.instance

    def register_observer(self, observer):
        self._observers.append(observer)

    def _notify(self):
        for observer in self._observers:
            observer.update(self._on)

    def set_on(self):
        self._on = True
        self._notify()

    def set_off(self):
        self._on = False
        self._notify()
    
    def is_on(self):
        return self._on


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
        

    
def inject_driver(driver = Driver):
    def inner(func):
        def wrapper(*args):
            d = driver().get()
            return func(*args, driver=d)
            
        return wrapper

    return inner

def inject_debug(debug = DebugMode):
    def inner(func):
        def wrapper(*args):
            return func(*args, debug=DebugMode())
            
        return wrapper

    return inner
