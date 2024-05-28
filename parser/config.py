import yaml
import debug
import driver


def _read_config():
    with open('config.yaml', 'r') as file:
        return yaml.safe_load(file)


def initialize():
    config = _read_config()
    drv = driver.get()
    debug.initialize([drv])
    if config['debug']:
        debug.get().set_on()

