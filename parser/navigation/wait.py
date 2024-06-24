from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as ec
from selenium.webdriver.support.wait import WebDriverWait
from selenium.common.exceptions import TimeoutException
from common.driver import inject_driver


#TODO: change print to logging
class LocatableSourceWait():
    @inject_driver()
    def until(self, source, driver = None):
        try:
            WebDriverWait(driver, 5).until(
                ec.presence_of_element_located((By.XPATH, source))
            )
        except TimeoutException:
            print(f'error in locatable wait: {source}')

class ClickableSourceWait():
    @inject_driver()
    def until(self, source, driver = None):
        try:
            WebDriverWait(driver, 5).until(
                ec.element_to_be_clickable((By.XPATH, source))
            )
        except TimeoutException:
            print(f'error in clickable wait: {source}')
