def get():
    return DebugMode()


def initialize(observers):
    mode = DebugMode()
    for observer in observers:
        mode.register_observer(observer)


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
