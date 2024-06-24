import yaml
from common.debug import inject_mode

class Config:
    def __new__(cls):
        if not hasattr(cls, 'instance'):
            cls.instance = super(Config, cls).__new__(cls)

        return cls.instance

    def __init__(self):
        with open('config.yaml', 'r') as file:
            self._config = yaml.safe_load(file)

    @inject_mode()
    def set_debug_mode(self, driver, debug_mode=None):
        debug_mode.register_observer(driver)
        if self._config.get('debug'):
            debug_mode.set_on()

    def get_max_page_to_iterate(self):
        if page_number := self._config.get('max_page_to_iterate'):
            return page_number
